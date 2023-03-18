<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Shoutbox</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="messages"></div>
    <form id="shoutbox-form">
        <textarea id="message" placeholder="Type your message..." rows="4" cols="50"></textarea>
        <button type="submit">Post</button>
    </form>
    <div id="pagination"></div>

    <script>
        $(document).ready(function() {
            let currentPage = 1;
            const messagesPerPage = 10;

            function loadMessages(page) {
                $.ajax({
                    url: 'get_messages.php',
                    data: {page: page, messagesPerPage: messagesPerPage},
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let messages = '';
                        $.each(response.messages, function(index, message) {
                            const bgColor = message.color;
                            messages += '<div class="message-box" style="background-color:' + bgColor + '">' + message.id + '. ' + message.message + '</div>';
                        });
                        $('#messages').html(messages);
                        generatePagination(response.totalPages);
                    }
                });
            }

            function generatePagination(totalPages) {
                let pagination = '';
                for (let i = 1; i <= totalPages; i++) {
                    pagination += '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a> ';
                }
                $('#pagination').html(pagination);
            }

            loadMessages(currentPage);

            $('#shoutbox-form').submit(function(e) {
                e.preventDefault();
                const messageText = $('#message').val().trim();

                if (messageText === '') {
                    return;
                }

                $.ajax({
                    url: 'post_message.php',
                    data: {message: messageText},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            loadMessages(currentPage);
                            $('#message').val('');
                        }
                    }
                });
            });

            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                currentPage = $(this).data('page');
                loadMessages(currentPage);
            });
        });
    </script>
</body>
</html>

