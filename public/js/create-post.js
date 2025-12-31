// Character counter
document.getElementById('postContent').addEventListener('input', function () {
    const length = this.value.length;
    const maxLength = 5000;
    const counter = document.querySelector('.character-count');
    counter.textContent = `${length}/${maxLength}`;
    counter.style.color = length > maxLength * 0.9 ? '#f02849' : '#65676b';
});

// Preview media function
function previewMedia(input) {
    const file = input.files[0];
    if (!file) return;

    const previewContainer = document.getElementById('mediaPreview');
    const previewImage = document.getElementById('previewImage');
    const previewVideo = document.getElementById('previewVideo');

    previewImage.style.display = 'none';
    previewVideo.style.display = 'none';

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else if (file.type.startsWith('video/')) {
        if (previewVideo.src) URL.revokeObjectURL(previewVideo.src);
        previewVideo.src = URL.createObjectURL(file);
        previewVideo.style.display = 'block';
        previewVideo.load();
    }
    previewContainer.style.display = 'block';
}

// Remove media
function removeMedia() {
    const previewContainer = document.getElementById('mediaPreview');
    const previewImage = document.getElementById('previewImage');
    const previewVideo = document.getElementById('previewVideo');
    const mediaInput = document.getElementById('mediaInput');

    if (previewVideo.src) URL.revokeObjectURL(previewVideo.src);

    previewImage.src = '';
    previewVideo.src = '';
    previewImage.style.display = 'none';
    previewVideo.style.display = 'none';
    previewContainer.style.display = 'none';
    mediaInput.value = '';
}

// Close create post
function closeCreatePost() {
    const content = document.getElementById('postContent').value;
    const hasMedia = document.getElementById('mediaInput').files.length > 0;

    if (content.trim() || hasMedia) {
        if (confirm('You have unsaved changes. Are you sure you want to close?')) {
            window.location.href = window.previousUrl;
        }
    } else {
        window.location.href = window.previousUrl;
    }
}

// Form submission
document.getElementById('createPostForm').addEventListener('submit', function (e) {
    const submitBtn = document.getElementById('submitBtn');
    const content = document.getElementById('postContent').value.trim();
    const hasMedia = document.getElementById('mediaInput').files.length > 0;

    if (!content && !hasMedia) {
        e.preventDefault();
        alert('Please add some content or media to your post.');
        return false;
    }

    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>Posting...</span>';
});

// ESC key closes post
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeCreatePost();
});
