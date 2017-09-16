<?=$navigationMenu;?>

<form class="form form--add-lot container<?=!empty($errors) ? ' form--invalid' : '';?>" action="add.php" method="post" enctype="multipart/form-data">
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <div class="form__item<?=key_exists('lot-name', $errors) ? ' form__item--invalid' : '';?>">
      <label for="lot-name">Наименование</label>
      <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=key_exists('lot-name', $_POST) ? $_POST['lot-name'] : ''; ?>" required>
      <span class="form__error"><?=key_exists('lot-name', $errors) ? implode('', $errors['lot-name']) : '' ?></span>
    </div>
    <div class="form__item<?=key_exists('category', $errors) ? ' form__item--invalid' : '';?>">
      <label for="category">Категория</label>
      <select id="category" name="category" required>
        <option>Выберите категорию</option>
        <?php foreach($goodsCategory as $value): ?>
          <option value="<?=$value; ?>" <?=key_exists('category', $_POST) && $_POST['category'] == $value ? 'selected' : '' ?>><?=$value;?></option>   
        <?php endforeach; ?>
      </select>
      <span class="form__error"><?=key_exists('category', $errors) ? implode('', $errors['category']) : '' ?></span>
    </div>
  </div>
  <div class="form__item form__item--wide<?=key_exists('message', $errors) ? ' form__item--invalid' : '';?>">
    <label for="message">Описание</label>
    <textarea id="message" name="message" placeholder="Напишите описание лота" required><?=key_exists('message', $_POST) ? $_POST['message'] : ''; ?></textarea>
    <span class="form__error"><?=key_exists('message', $errors) ? implode('', $errors['message']) : '' ?></span>
  </div>

  <div class="form__item form__item--file<?=key_exists('add-img', $errors) ? ' form__item--invalid' : '';?>">
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
    <span class="form__error"><?=key_exists('add-img', $errors) ? implode('', $errors['add-img']) : '' ?></span>
  </div>
  <div class="form__container-three">
    <div class="form__item form__item--small<?=key_exists('lot-rate', $errors) ? ' form__item--invalid' : '';?>">
      <label for="lot-rate">Начальная цена</label>
      <input id="lot-rate" type="number" name="lot-rate" placeholder="0" value="<?=key_exists('lot-rate', $_POST) ? $_POST['lot-rate'] : ''; ?>" required>
      <span class="form__error"><?=key_exists('lot-rate', $errors) ? implode('', $errors['lot-rate']) : '' ?></span>
    </div>
    <div class="form__item form__item--small<?=key_exists('lot-step', $errors) ? ' form__item--invalid' : '';?>">
      <label for="lot-step">Шаг ставки</label>
      <input id="lot-step" type="number" name="lot-step" placeholder="0" value="<?=key_exists('lot-step', $_POST) ? $_POST['lot-step'] : ''; ?>" required>
      <span class="form__error"><?=key_exists('lot-step', $errors) ? implode('', $errors['lot-step']) : '' ?></span>
    </div>
    <div class="form__item<?=key_exists('lot-date', $errors) ? ' form__item--invalid' : '';?>">
      <label for="lot-date">Дата завершения</label>
      <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017" value="<?=key_exists('lot-date', $_POST) ? $_POST['lot-date'] : ''; ?>" required>
      <span class="form__error"></span>
    </div><span class="form__error"><?=key_exists('lot-date', $errors) ? implode('', $errors['lot-date']) : '' ?></span>
  </div>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Добавить лот</button>
</form>