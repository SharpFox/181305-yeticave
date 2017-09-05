<?=$navigationMenu;?>
<?php if (!empty($errors)): ?>
<form class="form form--invalid form--add-lot container" action="add.php" method="post" enctype="multipart/form-data">
<?php else: ?>
<form class="form form--add-lot container" action="add.php" method="post" enctype="multipart/form-data">
<?php endif; ?>
  <h2>Добавление лота</h2>
    <div class="form__container-two">
      <?php if (in_array('lot-name', $errors)): ?>
      <div class="form__item form__item--invalid">
      <?php else: ?>
      <div class="form__item">
      <?php endif; ?>
       <label for="lot-name">Наименование</label>
       <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" required>
       <span class="form__error"></span>
      </div>
      <?php if (in_array('category', $errors)): ?>
      <div class="form__item form__item--invalid">
      <?php else: ?>
      <div class="form__item">
      <?php endif; ?>
        <label for="category">Категория</label>
        <select id="category" name="category" required>
          <option>Выберите категорию</option>
          <?php foreach($goodsCategory as $value): ?>
            <option><?=$value;?></option>   
          <?php endforeach; ?>
        </select>
        <span class="form__error"></span>
      </div>
    </div>
    <?php if (in_array('message', $errors)): ?>
    <div class="form__item form__item--wide form__item--invalid">
    <?php else: ?>
    <div class="form__item form__item--wide">
    <?php endif; ?>
      <label for="message">Описание</label>
      <textarea id="message" name="message" placeholder="Напишите описание лота" required></textarea>
      <span class="form__error"></span>
    </div>
    <div class="form__item form__item--file"> <!-- form__item--uploaded -->
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
    </div>
    <div class="form__container-three">
      <?php if (in_array('lot-rate', $errors)): ?>
      <div class="form__item form__item--small form__item--invalid">
      <?php else: ?>
      <div class="form__item form__item--small">
      <?php endif; ?>
        <label for="lot-rate">Начальная цена</label>
        <input id="lot-rate" type="number" name="lot-rate" placeholder="0" required>
        <span class="form__error"></span>
      </div>
      <?php if (in_array('lot-step', $errors)): ?>
      <div class="form__item form__item--small form__item--invalid">
      <?php else: ?>
      <div class="form__item form__item--small">
      <?php endif; ?>
        <label for="lot-step">Шаг ставки</label>
        <input id="lot-step" type="number" name="lot-step" placeholder="0" required>
        <span class="form__error"></span>
      </div>
      <?php if (in_array('lot-date', $errors)): ?>
      <div class="form__item form__item--small form__item--invalid">
      <?php else: ?>
      <div class="form__item form__item--small">
      <?php endif; ?>
      <div class="form__item">
        <label for="lot-date">Дата завершения</label>
        <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017" required>
        <span class="form__error"></span>
      </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
  </form>