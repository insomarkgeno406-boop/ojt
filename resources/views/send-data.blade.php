<!DOCTYPE html>
<html>
<head>
    <title>Send Grades</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 12px 0 6px;
            font-weight: bold;
            color: #333;
        }

        input[type="file"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            margin-top: 20px;
            background-color: #3490dc;
            color: white;
            border: none;
            padding: 12px 18px;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #2779bd;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .success-message {
            color: green;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .request-list {
            margin-top: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .request-list ul {
            padding-left: 20px;
        }

        .request-list strong {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📎 Send Grades</h2>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif

    <form action="{{ route('intern.uploadDocx') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="grade_doc">Upload .doc or .docx file:</label>
        <input type="file" name="grade_doc" accept=".doc,.docx" required>

        <label for="semester">Select Document Type:</label>
        <select name="semester" required>
            <option value="">-- Choose --</option>
            <option value="3rd">Certificate</option>
            <option value="4th">Evaluation Form</option>
        </select>

        <button type="submit">✅ Submit Data</button>
    </form>

    @if(!empty($requests))
        <div class="request-list">
            <strong>📢 Pending Document Requests:</strong>
            <ul>
                @foreach($requests as $req)
                    <li>{{ ucfirst($req) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('intern.dashboard') }}" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>
