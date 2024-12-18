/*
jQuery(document).ready(function($) {
    setTimeout(function() { 
        $('#id_cancel').click(function() {
            $.ajax({
                url: 'block_chatbot.php', 
                method: 'GET',
                success: function(data) {
                    $('#chatblock').html(data);
                },
                error: function() {
                    alert('Fehler beim Laden der Daten.');
                }
            });
        });
    }, 2000); 
}); */

jQuery(document).ready(function($) {
    $('#id_submitbutton').click(function(event) {
        event.preventDefault();
        console.log('Button wurde geklickt.');

        setTimeout(function() { 
            console.log('AJAX-Anfrage wird gesendet...');
            $.ajax({
                url: 'block_chatbot.php', 
                method: 'GET',
                success: function(data) {
                    $('#chatblock').html(data);
                    console.log('Daten erfolgreich geladen.');
                },
                error: function() {
                    alert('Fehler beim Laden der Daten.');
                }
            });
        }, 1000); 
    });
});

