<?php
$insert = false;
$full_name = "";

if (isset($_POST['fullName'])) {
    // Database connection
    $server = "localhost";
    $username = "root"; 
    $password = "";
    $database = "paws";
    
    $con = mysqli_connect($server, $username, $password, $database);

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize inputs
    $donation_type = mysqli_real_escape_string($con, $_POST['donationType']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $full_name = mysqli_real_escape_string($con, $_POST['fullName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $payment_method = isset($_POST['paymentMethod']) ? mysqli_real_escape_string($con, $_POST['paymentMethod']) : '';
    $card_number = mysqli_real_escape_string($con, $_POST['cardNumber']);
    $exp_date = mysqli_real_escape_string($con, $_POST['expDate']);
    $cvv = mysqli_real_escape_string($con, $_POST['cvv']);

    // Validate required fields
    if (empty($donation_type) || empty($amount) || empty($full_name) || empty($email) || 
        empty($payment_method) || empty($card_number) || empty($exp_date) || empty($cvv)) {
        echo "<p style='color: red; text-align: center;'>All fields are required!</p>";
    } else {
        // Corrected SQL query - removed problematic quotes
        $sql = "INSERT INTO donations (donation_type, amount, full_name, email, payment_method, card_number, exp_date, cvv)
                VALUES ('$donation_type', '$amount', '$full_name', '$email', '$payment_method', '$card_number', '$exp_date', '$cvv')";

        if ($con->query($sql)) {
            $insert = true;
        } else {
            echo "<p style='color: red; text-align: center;'>Error: " . $con->error . "</p>";
        }
    }

    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Our Animals - Paws Haven</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        .donation-header {
            background-color: #2c3e50;
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }

        .donation-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .donation-form {
            display: grid;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .donation-amounts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .amount-btn {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 2px solid #e74c3c;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
        }

        .amount-btn.selected {
            background-color: #e74c3c;
            color: white;
        }

        .payment-methods {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
        }

        .payment-method {
            flex: 1;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .payment-method.selected {
            border-color: #e74c3c;
            background-color: #fcebec;
        }

        #donate-btn {
            background-color: #e74c3c;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 4px;
            font-size: 1.1rem;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        #donate-btn:hover {
            background-color: #c0392b;
        }

        .security-notice {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .success-message {
            color: green;
            text-align: center;
            margin: 20px 0;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .donation-container {
                padding: 1rem;
            }
            
            .payment-methods {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="donation-header">
        <h1>Support Our Animals</h1>
        <p>Your donation helps provide food, shelter, and medical care</p>
    </header>

    <?php if ($insert): ?>
        <div class="success-message">
            <h2>Thank you for your donation, <?php echo htmlspecialchars($full_name); ?>!</h2>
            <p>We appreciate your support for our animals.</p>
        </div>
    <?php endif; ?>

    <div class="donation-container">
        <form class="donation-form" id="donationForm" method="POST" action="donate.php">
            <div class="form-group">
                <label for="donationType">Donation Type</label>
                <select name="donationType" id="donationType" required>
                    <option value="">Select One</option>
                    <option value="single">One-time Donation</option>
                    <option value="monthly">Monthly Donation</option>
                </select>
            </div>

            <div class="form-group">
                <label>Donation Amount (₹)</label>
                <div class="donation-amounts">
                    <div class="amount-btn" data-amount="50">₹50</div>
                    <div class="amount-btn" data-amount="100">₹100</div>
                    <div class="amount-btn" data-amount="200">₹200</div>
                    <div class="amount-btn" data-amount="500">₹500</div>
                    <div class="amount-btn" data-amount="1000">₹1000</div>
                    <div class="amount-btn" data-amount="other">Other</div>
                </div>
                <input type="number" name="amount" id="customAmount" placeholder="Enter custom amount" required style="display: none;">
            </div>

            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" name="fullName" id="fullName" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <div class="payment-methods">
                    <div class="payment-method selected" data-method="Credit/Debit Card">
                        Credit/Debit Card
                    </div>
                    <div class="payment-method" data-method="PayPal">
                        PayPal
                    </div>
                </div>
                <input type="hidden" name="paymentMethod" id="paymentMethod" value="Credit/Debit Card" required>
            </div>

            <div class="form-group">
                <label for="cardNumber">Card Number</label>
                <input type="text" name="cardNumber" id="cardNumber" required pattern="[0-9\s]{13,19}">
            </div>

            <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="expDate">Expiration Date</label>
                    <input type="month" name="expDate" id="expDate" required>
                </div>
                <div>
                    <label for="cvv">CVV</label>
                    <input type="number" name="cvv" id="cvv" required min="100" max="999">
                </div>
            </div>

            <button type="submit" id="donate-btn">Donate Now</button>
            <p class="security-notice">
                <i class="fas fa-lock"></i> Your information is secure. We use 256-bit SSL encryption
            </p>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Amount selection
            const amountButtons = document.querySelectorAll('.amount-btn');
            const customAmountInput = document.getElementById('customAmount');
            const amountInput = document.querySelector('input[name="amount"]');

            amountButtons.forEach(button => {
                button.addEventListener('click', () => {
                    amountButtons.forEach(btn => btn.classList.remove('selected'));
                    button.classList.add('selected');
                    
                    if (button.dataset.amount === 'other') {
                        customAmountInput.style.display = 'block';
                        customAmountInput.value = '';
                        customAmountInput.focus();
                    } else {
                        customAmountInput.style.display = 'none';
                        amountInput.value = button.dataset.amount;
                    }
                });
            });

            // Payment method selection
            const paymentMethods = document.querySelectorAll('.payment-method');
            const paymentMethodInput = document.getElementById('paymentMethod');

            paymentMethods.forEach(method => {
                method.addEventListener('click', () => {
                    paymentMethods.forEach(m => m.classList.remove('selected'));
                    method.classList.add('selected');
                    paymentMethodInput.value = method.dataset.method;
                });
            });

            // Form validation
            const form = document.getElementById('donationForm');
            form.addEventListener('submit', function(e) {
                // Additional validation can be added here
                if (!document.querySelector('.amount-btn.selected') && !customAmountInput.value) {
                    alert('Please select or enter a donation amount');
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>