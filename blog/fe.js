// Initialize Firebase
// Replace these with your Firebase project settings
const firebaseConfig = {
    apiKey: "AIzaSyB_N6NbT5ibngD57Lomgux_NVUs9opbnQk",
    authDomain: "blog-decea.firebaseapp.com",
    projectId: "blog-decea",
    storageBucket: "blog-decea.appspot.com",
    messagingSenderId: "591423046157",
    appId: "1:591423046157:web:a4218ab1bbbacf0ac88b44"
};

firebase.initializeApp(firebaseConfig);
const storage = firebase.storage();

// Function to upload file to Firebase Storage
async function uploadFile(file) {
    const storageRef = storage.ref();
    const fileRef = storageRef.child(`blog_files/${file.name}`);
    await fileRef.put(file);
    return await fileRef.getDownloadURL();
}

// Function to save HTML file to the server (simulated)
async function saveHTMLFile(filename, content) {
    // Simulate saving the file to the root directory
    console.log(`Saving HTML file: ${filename}`);
    console.log(content);
    // You can replace this with actual server-side code to save the file
}