<main>

    <?=$navigationMenu;?>

    <form class="form container<?=!empty($errors) ? ' form--invalid' : '';?>" action="login.php" method="post" >
        <h2>Вход</h2>
        <div class="form__item<?=key_exists('email', $errors) ? ' form__item--invalid' : '';?>">
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=key_exists('email', $_POST) ? $_POST['email'] : ''; ?>" required>
            <span class="form__error"><?=key_exists('email', $errors) ? implode(', ', $errors['email']) : '' ?></span>        
        </div>
        <div class="form__item form__item--last<?=key_exists('password', $errors) ? ' form__item--invalid' : '';?>">
            <label for="password">Пароль*</label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" required>
            <span class="form__error"><?=key_exists('password', $errors) ? implode(', ', $errors['password']) : '' ?></span>        
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
    
</main>