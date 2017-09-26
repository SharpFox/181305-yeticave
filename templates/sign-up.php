<main>
  
<?=$navigationMenu;?>

  <form class="form container<?=!empty($errors) ? ' form--invalid' : '';?>" action="sign-up.php" method="post" enctype="multipart/form-data">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item<?=key_exists('email', $errors) ? ' form__item--invalid' : '';?>">
      <label for="email">E-mail*</label>
      <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=key_exists('email', $_POST) ? $_POST['email'] : ''; ?>" required>
      <span class="form__error"><?=key_exists('email', $errors) ? implode(', ', $errors['email']) : '' ?></span>
    </div>
    <div class="form__item<?=key_exists('password', $errors) ? ' form__item--invalid' : '';?>">
      <label for="password">Пароль*</label>
      <input id="password" type="password" name="password" placeholder="Введите пароль" required>
      <span class="form__error"><?=key_exists('password', $errors) ? implode(', ', $errors['password']) : '' ?></span>
    </div>
    <div class="form__item<?=key_exists('name', $errors) ? ' form__item--invalid' : '';?>">
      <label for="name">Имя*</label>
      <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=key_exists('name', $_POST) ? $_POST['name'] : ''; ?>" required>
      <span class="form__error"><?=key_exists('name', $errors) ? implode(', ', $errors['name']) : '' ?></span>
    </div>
    <div class="form__item<?=key_exists('message', $errors) ? ' form__item--invalid' : '';?>">
      <label for="message">Контактные данные*</label>
      <textarea id="message" name="message" placeholder="Напишите как с вами связаться" required><?=key_exists('message', $_POST) ? $_POST['message'] : ''; ?></textarea>
      <span class="form__error"><?=key_exists('message', $errors) ? implode(', ', $errors['message']) : '' ?></span>
    </div>
    <div class="form__item form__item--file form__item--last<?=key_exists('add-img', $errors) ? ' form__item--invalid' : '';?>">
      <label>Изображение</label>
      <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
          <img src="../img/avatar.jpg" width="113" height="113" alt="Изображение лота">
        </div>
      </div>
      <div class="form__input-file">
        <input class="visually-hidden" name="add-img" type="file" id="photo2" value="">
        <label for="photo2">
          <span>+ Добавить</span>
        </label>
      </div>
      <span class="form__error"><?=key_exists('add-img', $errors) ? implode(', ', $errors['add-img']) : '' ?></span>
    </div>
    <span class="form__error form__error--bottom"><?=key_exists('newUser', $errors) ? implode(', ', $errors['newUser']) : '' ?></span>
    <span class="form__error form__error--bottom"><?=key_exists('newUser', $errors) ? '' : 'Пожалуйста, исправьте ошибки в форме.' ?></span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
  </form>
</main>