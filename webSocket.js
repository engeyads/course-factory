var connection = new WebSocket('ws://mercuryt.mercury-training.com:8080');

connection.onopen = function () {
    // Connection is open and ready to use
};

connection.onerror = function (error) {
    // Handle any errors that occur
};

connection.onmessage = function (message) {
    var cdrData = JSON.parse(message.data);
    console.log(cdrData);
    // Process CDR data
};
