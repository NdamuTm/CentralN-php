document.querySelectorAll('.reply').forEach(button => {
    button.addEventListener('click', function() {
        // Check if a reply input already exists within this element's parent
        const existingReplyInput = this.parentElement.querySelector('.replyto-comment');
        if (existingReplyInput) {
            // If it exists, focus on it and return
            existingReplyInput.querySelector('.comment-input').focus();
            return;
        }
        function getPostIdFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            const postId = urlParams.get('id');
            return postId;
        }

        const postId = getPostIdFromURL();

        // If it doesn't exist, create a new reply input
        let commentBox = document.createElement('div');
        commentBox.className = 'comment';
        commentBox.innerHTML = `
            <div class="user-icon">
                <img src="/assets/icons/user icon.png" alt="User Icon">
            </div>
            <form action="/assets/Utils/posting.php" method="post" enctype="multipart/form-data" class="replyto-comment">
             <input type="hidden" name="action" value="addReply">
                        <input class="comment-input" type="text" placeholder="Reply" name="comment">
                <input type="hidden" name="parent_comment_id" value="${this.dataset.commentId}">
                <input type="hidden" name="post_id" value="${postId}">
                <button class="btn">
                    Submit
                </button>
                    </form>`;
        this.parentElement.appendChild(commentBox);
    });
});