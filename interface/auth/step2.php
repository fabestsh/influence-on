<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>InfluenceON - Complete Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }

        .form-container {
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
            max-width: 750px;
            width: 100%;
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
            color: #343a40;
        }

        .role-selector {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .role-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            background: #dee2e6;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-btn.active {
            background: #0d6efd;
            color: white;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
        }

        .form-check {
            margin-bottom: 8px;
        }

        .hidden {
            display: none;
        }

        .btn-primary {
            border-radius: 30px;
            padding: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
        }

        .terms {
            font-size: 0.875rem;
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }

        @media (min-width: 768px) {
            .influencer-row {
                display: flex;
                gap: 20px;
            }

            .influencer-row>div {
                flex: 1;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1 class="logo">Complete Your Profile</h1>

        <form id="registrationForm" action="php/step2.php" method="POST">
            <div class="role-selector">
                <button type="button" class="role-btn active" data-role="business">Business</button>
                <button type="button" class="role-btn" data-role="influencer">Influencer</button>
                <input type="hidden" name="role" value="business" id="roleInput" />
            </div>

            <!-- Business Mode -->
            <div id="businessFields">
                <div class="mb-3">
                    <input type="text" class="form-control" id="businessName" name="businessName"
                        placeholder="Business Name" />
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="industry" name="industry" placeholder="Industry" />
                </div>
                <div class="mb-3">
                    <input type="url" class="form-control" id="website" name="website"
                        placeholder="Website (Optional)" />
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="businessContact" name="businessContact"
                        placeholder="Business Contact Info" />
                </div>
            </div>

            <!-- Influencer Mode -->
            <div id="influencerFields" class="hidden">
                <div class="influencer-row">
                    <div>
                        <div class="mb-3">
                            <input type="url" class="form-control" name="socialLinks[instagram]"
                                placeholder="Instagram URL" />
                        </div>
                        <div class="mb-3">
                            <input type="url" class="form-control" name="socialLinks[tiktok]"
                                placeholder="TikTok URL" />
                        </div>
                        <div class="mb-3">
                            <input type="url" class="form-control" name="socialLinks[youtube]"
                                placeholder="YouTube URL" />
                        </div>
                    </div>
                    <div>
                        <label class="form-label d-block">Areas of Expertise</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="expertise[]" value="fashion"
                                id="expFashion">
                            <label class="form-check-label" for="expFashion">Fashion</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="expertise[]" value="food"
                                id="expFood">
                            <label class="form-check-label" for="expFood">Food</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="expertise[]" value="fitness"
                                id="expFitness">
                            <label class="form-check-label" for="expFitness">Fitness</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="expertise[]" value="tech"
                                id="expTech">
                            <label class="form-check-label" for="expTech">Tech</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="expertise[]" value="travel"
                                id="expTravel">
                            <label class="form-check-label" for="expTravel">Travel</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <input type="number" class="form-control" id="age" name="age" placeholder="Your Age" />
                </div>

                <div class="mb-3">
                    <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Short Bio..."></textarea>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Complete Registration</button>
            </div>
        </form>

        <p class="terms">
            By signing up, you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
        </p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const roleInput = document.getElementById("roleInput");
            const businessFields = document.getElementById("businessFields");
            const influencerFields = document.getElementById("influencerFields");
            const roleButtons = document.querySelectorAll(".role-btn");

            roleButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    roleButtons.forEach((btn) => btn.classList.remove("active"));
                    button.classList.add("active");

                    const role = button.dataset.role;
                    roleInput.value = role;

                    if (role === "business") {
                        businessFields.classList.remove("hidden");
                        influencerFields.classList.add("hidden");
                    } else {
                        businessFields.classList.add("hidden");
                        influencerFields.classList.remove("hidden");
                    }
                });
            });
        });
    </script>
</body>

</html>