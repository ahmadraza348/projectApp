console.log('🚀 notification.js loaded');

console.log('authUserId:', window.authUserId);
console.log('Echo:', window.Echo);

if (!window.authUserId) {
    console.error('❌ authUserId is missing');
}

if (!window.Echo) {
    console.error('❌ Echo is missing');
}

if (window.authUserId && window.Echo) {

    const channelName = `App.Models.User.${window.authUserId}`;


    window.Echo
        .private(channelName)
        .notification((notification) => {

            console.log('🔥 REAL-TIME NOTIFICATION RECEIVED');
            console.log(notification);

        });
}