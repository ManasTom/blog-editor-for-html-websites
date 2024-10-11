let username = 'user 01';

document.getElementById("displayUserName").innerText = username;

// ************************************************************************************
// script for quill editor
// ************************************************************************************

const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    // ['blockquote', 'code-block'],
    ['link', 'image', 'video'],

    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
    [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
    [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
    // [{ 'direction': 'rtl' }],                         // text direction

    // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    [{ 'font': [] }],
    [{ 'align': [] }],

    // ['clean']                                         // remove formatting button
];
const quill = new Quill('#bed_content_editor', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow',
});




// ************************************************************************************
// script to add hash tags
// ************************************************************************************

document.getElementById('add-tag-button').addEventListener('click', () => {
    const tagInput = document.getElementById('tag-input');
    const tagsContainer = document.getElementById('tags-container');
    const tags = tagInput.value.split(',').map(tag => tag.trim()).filter(tag => tag !== '');

    tags.forEach(tag => {
        const tagElement = document.createElement('div');
        tagElement.classList.add('tag');

        const tagName = document.createElement('span');
        tagName.textContent = `#${tag}`;

        const removeButton = document.createElement('button');
        removeButton.innerHTML = '&times;';
        removeButton.addEventListener('click', () => {
            tagsContainer.removeChild(tagElement);
        });

        tagElement.appendChild(tagName);
        tagElement.appendChild(removeButton);
        tagsContainer.appendChild(tagElement);
    });

    tagInput.value = '';
});

// ************************************************************************************
// script to add featured image
// ************************************************************************************

document.getElementById('upload-button').addEventListener('click', () => {
    document.getElementById('image-input').click();
});

document.getElementById('image-input').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('featured-image').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

document.getElementById('remove-button').addEventListener('click', () => {
    document.getElementById('featured-image').src = 'default-image.png'; // Set to default or placeholder image
    document.getElementById('image-input').value = ''; // Clear the input value
});




















document.addEventListener('DOMContentLoaded', function () {
    // Fetch the JSON data
    fetch('timestamp.json')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('adminTableBody');
            tableBody.innerHTML = ''; // Clear any existing content

            // Loop through the JSON data
            Object.keys(data).forEach(timestamp => {
                const post = data[timestamp];

                // Create table row
                const row = document.createElement('tr');

                // Title column
                const titleCell = document.createElement('td');
                titleCell.textContent = post.title;
                row.appendChild(titleCell);

                // Published Date column
                const dateCell = document.createElement('td');
                dateCell.textContent = new Date(timestamp).toLocaleString();
                row.appendChild(dateCell);

                // Actions column
                const actionsCell = document.createElement('td');
                const editButton = document.createElement('button');
                editButton.textContent = 'Edit';
                editButton.classList.add('btn', 'btn-primary');
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.classList.add('btn', 'btn-danger');

                // Append buttons to actions cell
                actionsCell.appendChild(editButton);
                actionsCell.appendChild(deleteButton);

                row.appendChild(actionsCell);

                // Append row to table body
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error loading JSON data:', error);
        });
});









document.addEventListener('DOMContentLoaded', function () {

    // Load categories from categories.json and populate the dropdown
    fetch('categories.json')
        .then(response => response.json())
        .then(data => {
            const categoryDropdown = document.getElementById('category-dropdown');
            categoryDropdown.innerHTML = ''; // Clear existing options


            // Add existing categories to the dropdown
            data.categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categoryDropdown.appendChild(option);

                const editPostData = localStorage.getItem('editPostData');
                if (editPostData) {
                    const postData = JSON.parse(editPostData);

                    console.log(postData.category);
                    var postcategory = postData.category;
                    if (category.toLowerCase() === postcategory.toLowerCase()) {
                        option.setAttribute('selected', 'selected');
                    }
                }
            });

            // Add the "Add New" option at the end
            const addNewOption = document.createElement('option');
            addNewOption.value = 'add-new';
            addNewOption.textContent = 'Add New Category';
            categoryDropdown.appendChild(addNewOption);
        })
        .catch(error => console.error('Error loading categories:', error));

    // Show the modal to add a new category when "Add New" is selected
    document.getElementById('category-dropdown').addEventListener('change', function () {
        if (this.value === 'add-new') {
            document.getElementById('addCategoryModal').style.display = 'block';
        }
    });

    // Save new category
    document.getElementById('saveCategoryButton').addEventListener('click', function () {
        const newCategory = document.getElementById('newCategoryInput').value.trim();
        if (newCategory) {
            // Add new category to categories.json
            fetch('categories.json')
                .then(response => response.json())
                .then(data => {
                    // Check if the category already exists
                    if (!data.categories.includes(newCategory)) {
                        data.categories.push(newCategory);

                        // Save the updated categories list to categories.json
                        return fetch('save_categories.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        });
                    } else {
                        alert('Category already exists!');
                    }
                })
                .then(() => {
                    // Add the new category to the dropdown
                    const option = document.createElement('option');
                    option.value = newCategory;
                    option.textContent = newCategory;
                    document.getElementById('category-dropdown').appendChild(option);
                    document.getElementById('category-dropdown').value = newCategory;

                    // Close the modal
                    document.getElementById('addCategoryModal').style.display = 'none';
                })
                .catch(error => console.error('Error saving category:', error));
        } else {
            alert('Please enter a category name.');
        }
    });
});
