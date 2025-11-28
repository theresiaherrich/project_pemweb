// LIVE.JS - Halaman Live Streaming Global Time

// Profile Dropdown Menu
function toggleProfileMenu(event) {
  event.preventDefault();
  const dropdown = document.querySelector('.profile-dropdown');
  dropdown.classList.toggle('active');
}

// Close profile menu when clicking outside
document.addEventListener('click', function(e) {
  const dropdown = document.querySelector('.profile-dropdown');
  const profileButton = document.querySelector('.user-profile');
  
  if (dropdown && !dropdown.contains(e.target)) {
    dropdown.classList.remove('active');
  }
});

// Logout function
function logout() {
  if (confirm("Yakin ingin logout?")) {
    window.location.href = "index.php?page=logout";
  }
}

// Navigation Active State
document.addEventListener('DOMContentLoaded', function() {
  const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
  const navLinks = document.querySelectorAll('.nav-menu a');
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href) {
      const linkPage = href.replace('.php', '');
      
      if (linkPage === currentPage || 
          (currentPage === '' && linkPage === 'home') ||
          (currentPage === 'index' && linkPage === 'home')) {
        link.classList.add('active');
      }
    }
    
    link.addEventListener('click', function() {
      navLinks.forEach(nav => nav.classList.remove('active'));
      this.classList.add('active');
    });
  });
});

// ============================================
// FITUR KHUSUS LIVE STREAMING
// ============================================

// Fungsi untuk mengubah video live stream
function switchStream(streamId, streamTitle, videoUrl, streamDescription, streamViewers) {
  document.getElementById('streamTitle').textContent = streamTitle;
  document.getElementById('streamDescription').textContent = streamDescription;
  document.getElementById('viewerCount').textContent = streamViewers;
  document.getElementById('mainVideo').src = videoUrl;
}

// Fungsi untuk toggle like
let isLiked = false;
function toggleLike() {
  const likeIcon = document.getElementById('likeIcon');
  const likeCount = document.getElementById('likeCount');
  if (isLiked) {
    isLiked = false;
    likeIcon.classList.remove('fas', 'fa-thumbs-up');
    likeIcon.classList.add('far', 'fa-thumbs-up');
    likeCount.textContent = '1.2K';
  } else {
    isLiked = true;
    likeIcon.classList.remove('far', 'fa-thumbs-up');
    likeIcon.classList.add('fas', 'fa-thumbs-up');
    likeCount.textContent = '1.3K';
  }
}

// Fungsi untuk membagikan stream
function shareStream() {
  const streamUrl = document.getElementById('mainVideo').src;
  const shareText = `Tonton siaran langsung: ${streamUrl}`;
  
  if (navigator.share) {
    navigator.share({
      title: 'Siaran Langsung Global Time',
      text: shareText,
      url: streamUrl,
    }).catch(error => console.log('Error sharing:', error));
  } else {
    alert('Fitur share tidak tersedia di browser ini.');
  }
}

// Fungsi untuk toggle fullscreen
function toggleFullscreen() {
  const videoContainer = document.getElementById('videoPlayer');
  if (document.fullscreenElement) {
    document.exitFullscreen();
  } else {
    videoContainer.requestFullscreen();
  }
}

// Fungsi untuk mengirim pesan ke live chat
function sendMessage() {
  const chatInput = document.getElementById('chatInput');
  const chatMessages = document.getElementById('chatMessages');
  const message = chatInput.value.trim();
  
  if (message) {
    const newMessage = document.createElement('div');
    newMessage.classList.add('chat-message');
    newMessage.innerHTML = `<strong>Pengguna:</strong> ${message} <small>baru saja</small>`;
    
    chatMessages.appendChild(newMessage);
    chatInput.value = '';
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }
}

// Fungsi untuk mengatur pengingat
function setReminder(button) {
  if (button.classList.contains('active')) {
    button.classList.remove('active');
    button.textContent = 'Ingatkan Saya';
  } else {
    button.classList.add('active');
    button.textContent = 'Pengingat Diatur';
  }
}

// Mengupdate counter jumlah chat
function updateChatCount() {
  const chatCount = document.getElementById('chatCount');
  const chatMessages = document.getElementById('chatMessages');
  if (chatCount && chatMessages) {
    chatCount.textContent = `${chatMessages.children.length} peserta`;
  }
}

// Event listener untuk input chat
const chatInput = document.getElementById('chatInput');
if (chatInput) {
  chatInput.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      sendMessage();
    }
  });
}

// Update jumlah chat secara berkala
setInterval(updateChatCount, 5000);