
        self.onnotificationclick = (event) => {
            if(event.notification.data.FCM_MSG.data.click_action){
               event.notification.close();
               event.waitUntil(clients.matchAll({
                    type: 'window'
               }).then((clientList) => {
                  for (const client of clientList) {
                      if (client.url === '/' && 'focus' in client)
                          return client.focus();
                      }
                  if (clients.openWindow)
                      return clients.openWindow(event.notification.data.FCM_MSG.data.click_action);
                  }));
            }
        };
        importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
               importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

        const firebaseConfig = {
        apiKey: "394b5f6700685f617cb5f1d052cf4826",
        authDomain: "p2p-test-5f5c6.firebaseapp.com",
        projectId: "p2p-test-5f5c6",
        storageBucket: "p2p-test-5f5c6.appspot.com",
        messagingSenderId: "109168593126",
        appId: "1:109168593126:web:3fcebf6e272b3260e3d327",
        measurementId: "G-FLLWEYC3DL"
        };

       const app = firebase.initializeApp(firebaseConfig);
       const messaging = firebase.messaging();

       messaging.setBackgroundMessageHandler(function (payload) {
       if (payload.notification.background && payload.notification.background == 1) {
          const title = payload.notification.title;
          const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
          };
          return self.registration.showNotification(
            title,
            options,
          );
       }
        });