window.addEventListener('load', function() {
    const data = JSON.parse(localStorage.getItem('formData'));

    if (data) {
    
        for (const key in data) {
            const field = document.querySelector(`[name=${key}]`);
            if (field) {
                field.value = data[key];
            }
        }

        localStorage.removeItem('formData');
    }
});


const myFile = document.getElementById('myFile');
const imagePreview = document.getElementById('imagePreview');

imagePreview.addEventListener('click', function() {
    event.preventDefault();
  myFile.click();
});

myFile.addEventListener('change', function(event) {
  const reader = new FileReader();

  reader.onload = function() {
    imagePreview.src = reader.result;
    imagePreview.style.objectFit = "cover";
  };

  reader.readAsDataURL(event.target.files[0]);
});


const tagInput = document.getElementById('tag-input');
const tagsContainer = document.getElementById('tags-container');
const hiddenTagsInput = document.getElementById('tags');

tagInput.addEventListener('input', function() {
    const value = tagInput.value;
    const lastChar = value.slice(-1);

    if (lastChar === ',') {
        tagInput.value = '';
        addTag(value.slice(0, -1).trim());
    }
});

function addTag(tag) {
    if (tag) {
        const tagElement = document.createElement('div');
        tagElement.classList.add('tag');
        tagElement.innerHTML = `<span class="remove-tag">${tag}</span><span>&times;</span>`;

        tagElement.querySelector('.remove-tag').addEventListener('click', function() {
            tagsContainer.removeChild(tagElement);
            removeTagFromHiddenInput(tag);
        });

        tagsContainer.appendChild(tagElement);

        // Add the tag to the hidden input field
        hiddenTagsInput.value += tag + ',';
    }
}

function removeTagFromHiddenInput(tag) {
    const hiddenTags = hiddenTagsInput.value.split(',');
    const index = hiddenTags.indexOf(tag);
    if (index !== -1) {
        hiddenTags.splice(index, 1);
        hiddenTagsInput.value = hiddenTags.join(',');
    }
}