
document.addEventListener("DOMContentLoaded", function() {
    // Fetch the timestamp.json file to get recent posts data
    fetch('timestamp.json')
        .then(response => response.json())
        .then(data => {
            const recentPostsContainer = document.querySelector('.base_container_col2');
            
            // Sort the timestamps in descending order to get the latest posts first
            const sortedTimestamps = Object.keys(data).sort((a, b) => new Date(b) - new Date(a));
            
            // Clear any existing content in the recent posts container
            recentPostsContainer.innerHTML = "<h3>Recent posts:</h3>";

            // Loop through the sorted timestamps and display the posts
            sortedTimestamps.forEach(timestamp => {
                const post = data[timestamp];
                console.log(post.visibility);
                
                const postCard = `
                    <div class="recentpost_card">
                        <h5>${post.title}</h5>
                        <img src="${post.featuredImage}" alt="">
                        <p>${post.firstLine}</p>
                        <a href="${post.url}">Read more</a>
                    </div>
                `;
                
                if(post.visibility === 'public'){
                   recentPostsContainer.innerHTML += postCard; 
                }
                
            });
        })
        .catch(error => console.error('Error fetching recent posts:', error));
});

