
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .dashboard {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #0862bd;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: bold;
        }

        .section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }

        .section h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #0862bd;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group button {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #0862bd;
            outline: none;
        }

        .form-group button {
            background-color: #0862bd;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #064b8e;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Add responsiveness */
        @media (max-width: 768px) {
            .dashboard {
                padding: 20px;
                width: 90%;
            }

            h1 {
                font-size: 1.5rem;
            }

            .form-group input,
            .form-group textarea {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <h1>Candidate Dashboard</h1>

        <!-- Edit Profile Section -->
        <div class="section" id="edit-profile">
            <h2>Edit Profile</h2>
            <form id="profile-form">
                <div class="form-group">
                    <label for="candidate-name">Name</label>
                    <input type="text" id="candidate-name" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="candidate-email">Email</label>
                    <input type="email" id="candidate-email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="candidate-info">About You</label>
                    <textarea id="candidate-info" rows="4" placeholder="Write something about yourself..."></textarea>
                </div>
                <div class="form-group">
                    <button type="button" onclick="saveProfile()">Save Profile</button>
                </div>
            </form>
        </div>

<!-- Submit Article Section -->
        <div class="section" id="submit-article">
            <h2>Submit an Article</h2>
            <form id="article-form">
                <div class="form-group">
                    <label for="article-title">Article Title</label>
                    <input type="text" id="article-title" placeholder="Enter article title" required>
                </div>
                <div class="form-group">
                    <label for="article-content">Article Content</label>
                    <textarea id="article-content" rows="6" placeholder="Write your article here..." required></textarea>
                </div>
                <div class="form-group">
                    <button type="button" onclick="submitArticle()">Submit Article</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Save Profile Function
        function saveProfile() {
            const name = document.getElementById('candidate-name').value;
            const email = document.getElementById('candidate-email').value;
            const info = document.getElementById('candidate-info').value;

            if (name && email) {
                alert(Profile saved!\nName: ${name}\nEmail: ${email}\nAbout: ${info});
            } else {
                alert('Please fill out all required fields in the profile form.');
            }
        }

        // Submit Article Function
        function submitArticle() {
            const title = document.getElementById('article-title').value;
            const content = document.getElementById('article-content').value;

            if (title && content) {
                alert(Article submitted!\nTitle: ${title}\nContent: ${content});
            } else {
                alert('Please fill out all required fields in the article form.');
            }
        }
    </script>
</body>

</html>