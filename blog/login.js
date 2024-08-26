// Your Firebase configuration
// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyB_N6NbT5ibngD57Lomgux_NVUs9opbnQk",
    authDomain: "blog-decea.firebaseapp.com",
    databaseURL: "https://blog-decea-default-rtdb.firebaseio.com",
    projectId: "blog-decea",
    storageBucket: "blog-decea.appspot.com",
    messagingSenderId: "591423046157",
    appId: "1:591423046157:web:a4218ab1bbbacf0ac88b44"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Handle login
document.getElementById('loginBtn').addEventListener('click', function () {
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    firebase.auth().signInWithEmailAndPassword(email, password)
        .then((userCredential) => {
            // Signed in 
            var user = userCredential.user;
            // Redirect to blog.html
            window.location.href = 'admin.html';
        })
        .catch((error) => {
            var errorCode = error.code;
            var errorMessage = error.message;
            alert('Error: ' + errorMessage);
        });
});

// Handle forgot password
document.getElementById('forgotPasswordLink').addEventListener('click', function () {
    var email = document.getElementById('email').value;

    if (email) {
        firebase.auth().sendPasswordResetEmail(email)
            .then(() => {
                alert('Password reset email sent!');
            })
            .catch((error) => {
                var errorCode = error.code;
                var errorMessage = error.message;
                alert('Error: ' + errorMessage);
            });
    } else {
        alert('Please enter your email address.');
    }
});
