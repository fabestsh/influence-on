<?php
    session_start();
    include '../../db/db_connection.php';

    function generateToken($id)
    {
        $secretKey = 'your-secret-key-here';
        $secretKey = str_pad($secretKey, 16, "\0");
        $encrypted = openssl_encrypt($id, 'AES-128-ECB', $secretKey);
        if ($encrypted === false) {
            die('Encryption failed');
        }

        return base64_encode($encrypted);
    }

    $query            = "SELECT id, name FROM users";
    $stmt             = $pdo->query($query);
    $contacts         = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $currentContactId = $_SESSION['contact_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Influences On</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/main.css">
</head>
<body>
<div class="d-flex chat-container">
  <div class="sidebar bg-white d-flex flex-column" id="sidebar">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
      <h5 class="m-0 text-primary fw-bold">Influences On</h5>
      <button class="btn btn-sm d-lg-none" id="closeSidebar">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <div class="d-flex align-items-center p-3 border-bottom">
      <img src="https://i.pravatar.cc/150?img=5" class="rounded-circle me-2" width="40" height="40">
      <div>
        <div class="fw-semibold">Alex Johnson</div>
        <small class="text-muted"><span class="badge bg-success rounded-circle p-1"></span> Online</small>
      </div>
    </div>

    <div class="p-3 border-bottom position-relative">
      <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
      <input type="text" class="form-control ps-5 rounded-pill" placeholder="Search...">
    </div>

    <div class="flex-grow-1 overflow-auto">
      <?php foreach ($contacts as $contact): ?>
      <div class="contact d-flex align-items-center p-2<?php echo($currentContactId == $contact['id']) ? 'active' : '' ?>"
           data-token="<?php echo htmlspecialchars(generateToken($contact['id'])) ?>">
        <img src="https://www.iconpacks.net/icons/2/free-user-icon-3296-thumb.png" class="rounded-circle me-2" width="40" height="40">
        <div class="flex-grow-1">
          <div class="fw-semibold"><?php echo htmlspecialchars($contact['name']) ?></div>
          <small class="text-muted">Last message...</small>
        </div>
        <small class="text-muted">Today</small>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="flex-grow-1 d-flex flex-column">
    <div class="d-flex justify-content-between align-items-center p-3 bg-white border-bottom">
      <button class="btn btn-sm d-lg-none me-2" id="openSidebar">
        <i class="bi bi-list"></i>
      </button>

      <div class="d-flex align-items-center">
        <img src="https://i.pravatar.cc/150?img=1" class="rounded-circle me-2" width="40" height="40">
        <div>
          <div class="fw-semibold" id="chatUserName">Sarah Williams</div>
          <small class="text-primary"><span class="badge bg-success rounded-circle p-1"></span> Online</small>
        </div>
      </div>

      <div>
        <button class="btn btn-sm text-muted me-1">
          <i class="bi bi-telephone"></i>
        </button>
        <button class="btn btn-sm text-muted me-1">
          <i class="bi bi-camera-video"></i>
        </button>
        <button class="btn btn-sm text-muted">
          <i class="bi bi-three-dots-vertical"></i>
        </button>
      </div>
    </div>

    <div class="flex-grow-1 p-3 overflow-auto chat-messages" id="chatMessages"></div>

    <div class="p-3 bg-white border-top position-relative">
      <div class="emoji-picker bg-white p-3 rounded shadow" id="emojiPicker">
        <div class="d-flex flex-wrap">
          <?php foreach (['😀', '😂', '😍', '😎', '👍', '❤️', '🔥', '🎉', '🙏', '🤔'] as $emoji): ?>
          <button class="btn btn-sm fs-5" onclick="insertEmoji('<?php echo $emoji ?>')"><?php echo $emoji ?></button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="d-flex align-items-center">
        <button class="btn btn-sm text-muted me-2" id="emojiBtn">
          <i class="bi bi-emoji-smile"></i>
        </button>
        <button class="btn btn-sm text-muted me-2" id="fileBtn">
          <i class="bi bi-paperclip"></i>
        </button>
        <input type="file" id="fileInput" class="file-input">
        <input type="text" class="form-control rounded-pill me-2" placeholder="Type a message..." id="chatInput">
        <button class="btn btn-primary rounded-circle p-2" id="sendBtn">
          <i class="bi bi-send"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
  $('#openSidebar').click(() => $('#sidebar').addClass('active'));
  $('#closeSidebar').click(() => $('#sidebar').removeClass('active'));

  $('#emojiBtn').click(() => $('#emojiPicker').toggle());
  $('#fileBtn').click(() => $('#fileInput').click());

  $('#fileInput').change(function() {
    if (this.files.length > 0) {
      const file = this.files[0];
      const reader = new FileReader();
      reader.onload = function(e) {
        const fileType = file.type.split('/')[0];
        if (fileType === 'image') {
          $('#chatMessages').append(`
            <div class="d-flex justify-content-end mb-2">
              <div class="message-sent text-white p-2 rounded-3">
                <img src="${e.target.result}" class="img-fluid rounded" style="max-height:200px">
                <div class="text-end small opacity-75 mt-1">${formatTime(new Date())}</div>
              </div>
            </div>
          `);
        } else {
          $('#chatMessages').append(`
            <div class="d-flex justify-content-end mb-2">
              <div class="message-sent text-white p-2 rounded-3">
                <div><i class="bi bi-file-earmark"></i> ${file.name}</div>
                <div class="text-end small opacity-75 mt-1">${formatTime(new Date())}</div>
              </div>
            </div>
          `);
        }
        scrollToBottom();
      }
      reader.readAsDataURL(file);
    }
  });

  $('.contact').click(function() {
    $('.contact').removeClass('active');
    $(this).addClass('active');
    const token = $(this).data('token');

    $.post('set_contact.php', {token}, (data) => {
      if (data.success) {
        $('#chatMessages').empty();
        data.messages.sort((a,b) => new Date(a.created_at) - new Date(b.created_at)).forEach(msg => {
          const isReceived = msg.sender_id == data.contact_id;
          $('#chatMessages').append(`
            <div class="d-flex justify-content-${isReceived ? 'start' : 'end'} mb-2">
              <div class="${isReceived ? 'message-received' : 'message-sent text-white'} p-2 rounded-3">
                <div>${msg.message}</div>
                <div class="text-end small ${isReceived ? 'text-muted' : 'opacity-75'} mt-1">${formatTime(msg.created_at)}</div>
              </div>
            </div>
          `);
        });
        scrollToBottom();
        $('#chatUserName').text($(this).find('.fw-semibold').text());
      }
    }, 'json');
  });

  $('#sendBtn').click(sendMessage);
  $('#chatInput').keypress(e => e.which === 13 && sendMessage());

  function sendMessage() {
    const message = $('#chatInput').val().trim();
    if (message) {
      $.post('send_message.php', {message}, (data) => {
        if (data.success) {
          $('#chatInput').val('');
          $('#chatMessages').append(`
            <div class="d-flex justify-content-end mb-2">
              <div class="message-sent text-white p-2 rounded-3">
                <div>${data.message}</div>
                <div class="text-end small opacity-75 mt-1">${formatTime(data.time)}</div>
              </div>
            </div>
          `);
          scrollToBottom();
          $('.contact.active').find('small:last').text('Just now');
        }
      }, 'json');
    }
  }

  function scrollToBottom() {
    $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
  }

  function formatTime(dateString) {
    return new Date(dateString).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
  }
});

function insertEmoji(emoji) {
  const input = $('#chatInput');
  input.val(input.val() + emoji);
  $('#emojiPicker').hide();
}
</script>
</body>
</html>