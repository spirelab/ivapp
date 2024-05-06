<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiValidation;
use App\Http\Traits\Notify;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Facades\App\Http\Controllers\User\SupportController;
use Illuminate\Validation\ValidationException;
use Stevebauman\Purify\Facades\Purify;


class SupportTicketController extends Controller
{
    use ApiValidation, Notify;

    public function ticketList()
    {
        if (auth()->id() == null) {
            return response()->json($this->withErrors('Something went wrong'));
        }
        try {
            $array = [];
            $tickets = tap(Ticket::where('user_id', auth()->id())->latest()
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array) {
                    $array['ticket'] = $query->ticket;
                    $array['subject'] = '[Ticket#' . $query->ticket . ']' . ucfirst($query->subject);
                    if ($query->status == 0) {
                        $array['status'] = trans('Open');
                    } elseif ($query->status == 1) {
                        $array['status'] = trans('Answered');
                    } elseif ($query->status == 2) {
                        $array['status'] = trans('Replied');
                    } elseif ($query->status == 3) {
                        $array['status'] = trans('Closed');
                    }
                    $array['lastReply'] = diffForHumans($query->last_reply);
                    return $array;
                });
            });

            if ($tickets) {
                return response()->json($this->withSuccess($tickets));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function ticketCreate(Request $request)
    {
        try {

            $this->newTicketValidation($request);
            $random = rand(100000, 999999);

            $ticket = SupportController::saveTicket($request, $random);

            $message = SupportController::saveMsgTicket($request, $ticket);

            $path = config('location.ticket.path');
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $image) {
                    try {
                        SupportController::saveAttachment($message, $image, $path);
                    } catch (\Exception $exp) {
                        return response()->json($this->withErrors('Could not upload your ' . $image));
                    }
                }
            }
            $this->ticketCreateNotify($ticket);
            return response()->json($this->withSuccess('Your Ticket has been pending'));

        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function newTicketValidation(Request $request)
    {
        $imgs = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');


        $this->validate($request, [
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($imgs, $allowedExts) {
                    foreach ($imgs as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            return response()->json($this->withErrors('Images MAX  2MB ALLOW!'));
                        }

                        if (!in_array($ext, $allowedExts)) {
                            return response()->json($this->withErrors('Only png, jpg, jpeg, pdf images are allowed'));
                        }
                    }
                    if (count($imgs) > 5) {
                        return response()->json($this->withErrors('Maximum 5 images can be uploaded'));
                    }
                }
            ],
            'subject' => 'required|max:100',
            'message' => 'required'
        ]);
    }

    public function ticketCreateNotify($ticket)
    {
        try {

            $user = auth()->user();
            $msg = [
                'name' => $user->fullname,
                'ticket_id' => $ticket->ticket
            ];

            $adminAction = [
                "link" => route('admin.ticket.view', $ticket->id),
                "icon" => "fas fa-ticket-alt text-white"
            ];

            $userAction = [
                "link" => route('user.ticket.list'),
                "icon" => "fas fa-ticket-alt text-white"
            ];

            $this->adminPushNotification('ADMIN_NOTIFY_USER_CREATE_TICKET', $msg, $adminAction);

            $currentDate = dateTime(Carbon::now());
            $this->sendMailSms($user, $type = 'USER_MAIL_CREATE_TICKET', [
                'name' => $user->fullname,
                'ticket_id' => $ticket->ticket,
                'date' => $currentDate
            ]);

            $this->mailToAdmin($type = 'ADMIN_MAIL_USER_CREATE_TICKET', [
                'name' => $user->fullname,
                'ticket_id' => $ticket->ticket,
                'date' => $currentDate
            ]);
            return true;

        } catch (\Exception $e) {
            return true;
        }
    }

    public function ticketView($ticketId)
    {
        try {
            $ticket = Ticket::with('messages')->where('ticket', $ticketId)->latest()->with('messages')->first();


            if (!$ticket) {
                return response()->json($this->withErrors('Something went wrong'));
            }
            $user = User::select(['image', 'username'])->where('id', auth()->id())->first();
            if (!$user) {
                return response()->json($this->withErrors('User Not Found'));
            }

            $data['id'] = $ticket->id;
            $data['page_title'] = "Ticket: #" . $ticketId . ' ' . $ticket->subject;
            $data['userImage'] = getFile(config('location.user.path') . optional($ticket->user)->image);
            $data['userUsername'] = optional($ticket->user)->username;
            if ($ticket->status == 0) {
                $data['status'] = trans('Open');
            } elseif ($ticket->status == 1) {
                $data['status'] = trans('Answered');
            } elseif ($ticket->status == 2) {
                $data['status'] = trans('Replied');
            } elseif ($ticket->status == 3) {
                $data['status'] = trans('Closed');
            }


            if ($ticket->messages) {
                foreach ($ticket->messages as $key => $message) {
                    $data['messages'][$key] = $message;
                    $data['messages'][$key]['adminImage'] = ($message->admin_id != null ? getFile(config('location.admin.path') . optional($message->admin)->image) : null);

                    $data['messages'][$key]['attachments'] = collect($message->attachments)->map(function ($attach, $key) {
                        $attach->attachment_path = getFile(config('location.ticket.path') . $attach->image);
                        $attach->attachment_name = trans('File') . ' ' . ($key + 1);
                    });
                }
            }

            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function ticketDownlaod($ticket_id)
    {
        $attachment = TicketAttachment::find($ticket_id);
        $file = $attachment->image;
        $path = config('location.ticket.path');
        $full_path = $path . '/' . $file;

        if (file_exists($full_path)) {
            $title = slug($attachment->supportMessage->ticket->subject);
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $mimetype = mime_content_type($full_path);
            header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
            header("Content-Type: " . $mimetype);
            return readfile($full_path);
        }
        return response()->json($this->withErrors('404'));
    }

    public function ticketReply(Request $request)
    {
        $ticket = Ticket::find($request->id);
        if (!$ticket) {
            return response()->json($this->withErrors('No data found'));
        }
        if ($request->replayTicket != 2 && $request->message == null) {
            return response()->json($this->withErrors('Message Field is required'));
        }

        try {
            $message = new TicketMessage();

            if ($request->replayTicket == 1) {
                $purifiedData = Purify::clean($request->except('_token', '_method'));
                $imgs = $request->file('attachments');
                $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

                $this->validate($request, [
                    'attachments' => [
                        'max:4096',
                        function ($attribute, $value, $fail) use ($imgs, $allowedExts) {
                            foreach ($imgs as $img) {
                                $ext = strtolower($img->getClientOriginalExtension());
                                if (($img->getSize() / 1000000) > 2) {
                                    return response()->json($this->withErrors('Images MAX  2MB ALLOW!'));
                                }

                                if (!in_array($ext, $allowedExts)) {
                                    return response()->json($this->withErrors('Only png, jpg, jpeg, pdf images are allowed'));
                                }
                            }
                            if (count($imgs) > 5) {
                                return response()->json($this->withErrors('Maximum 5 images can be uploaded'));
                            }
                        }
                    ],
                    'message' => 'required',
                ]);

                $ticket->status = 2;
                $ticket->last_reply = Carbon::now();
                $ticket->save();

                $message->ticket_id = $ticket->id;
                $message->message = $purifiedData['message'] ?? null;
                $message->save();

                $path = config('location.ticket.path');
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $image) {
                        try {
                            SupportController::saveAttachment($message, $image, $path);
                        } catch (\Exception $exp) {
                            return response()->json($this->withErrors('Could not upload your ' . $image));
                        }
                    }
                }

                $user = auth()->user();
                $msg = [
                    'name' => $user->fullname,
                    'ticket_id' => $ticket->ticket
                ];

                $adminAction = [
                    "link" => route('admin.ticket.view', $ticket->id),
                    "icon" => "fas fa-ticket-alt text-white"
                ];

                $userAction = [
                    "link" => route('user.ticket.view', $ticket->ticket),
                    "icon" => "fas fa-ticket-alt text-white"
                ];

                $this->adminPushNotification('ADMIN_NOTIFY_USER_REPLY_TICKET', $msg, $adminAction);
                $currentDate = dateTime(Carbon::now());

                $this->mailToAdmin($type = 'ADMIN_MAIL_USER_REPLY_TICKET', [
                    'name' => $user->fullname,
                    'ticket_id' => $ticket->ticket,
                    'date' => $currentDate
                ]);

                return response()->json($this->withSuccess('Ticket has been replied'));

            } elseif ($request->replayTicket == 2) {
                $ticket->status = 3;
                $ticket->last_reply = Carbon::now();
                $ticket->save();

                $user = auth()->user();
                $msg = [
                    'name' => $user->fullname,
                    'ticket_id' => $ticket->ticket
                ];

                $adminAction = [
                    "link" => route('admin.ticket.view', $ticket->id),
                    "icon" => "fas fa-ticket-alt text-white"
                ];

                $userAction = [
                    "link" => route('user.ticket.view', $ticket->ticket),
                    "icon" => "fas fa-ticket-alt text-white"
                ];

                $this->adminPushNotification('ADMIN_NOTIFY_USER_TICKET_CLOSE', $msg, $adminAction);

                $currentDate = dateTime(Carbon::now());
                $this->sendMailSms($user, $type = 'USER_MAIL_OWN_TICKET_CLOSE', [
                    'name' => $user->fullname,
                    'ticket_id' => $ticket->ticket,
                    'date' => $currentDate
                ]);

                $this->mailToAdmin($type = 'ADMIN_MAIL_USER_TICKET_CLOSE', [
                    'name' => $user->fullname,
                    'ticket_id' => $ticket->ticket,
                    'date' => $currentDate
                ]);
                return response()->json($this->withSuccess('Ticket has been closed'));
            }
            return response()->json($this->withErrors('Something went wrong'));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }
}
