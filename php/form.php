<form action="register.php" method="POST">
    <div>
        <label>
            <input type="radio" name="role" value="business" checked> Business
        </label>
        <label>
            <input type="radio" name="role" value="influencer"> Influencer
        </label>
    </div>

    <div>
        <input type="email" name="email" placeholder="Enter your email" required>
    </div>

    <div>
        <input type="password" name="password" placeholder="Create password" required>
    </div>

    <button type="submit">Create Account</button>
</form>
