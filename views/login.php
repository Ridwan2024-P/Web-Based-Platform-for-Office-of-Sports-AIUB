<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Login</title>
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .btn-primary {
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #004085;
        transform: translateY(-2px);
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
</style>
</head>
<body>
<section class="vh-100 d-flex align-items-center">
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="draw2.webp" class="img-fluid" alt="Sample image">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="card p-4">
                <form method="POST">
                    <h2 class="mb-4 text-center">Login</h2>

                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <div class="form-outline mb-4">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control form-control-lg rounded-pill" placeholder="Enter a valid email" required/>
                    </div>

                    <div class="form-outline mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg rounded-pill" placeholder="Enter password" required/>
                    </div>

                    <div class="text-center mt-4 pt-2">
                        <button type="submit" name="login" class="btn btn-primary btn-lg w-100">Login</button>
                        <p class="small fw-bold mt-3 mb-0 text-center">Don't have an account? <a href="views/register.php" class="link-danger">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
</body>
</html>
