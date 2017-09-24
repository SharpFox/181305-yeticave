<main>

  <?=$navigationMenu;?>

  <section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
    <?php foreach ($ratedContent as $key => $value): ?>
      <tr class="rates__item">
        <td class="rates__info">
          <div class="rates__img">
            <img src=<?=$value['url'];?> width="54" height="40" alt=<?=$value['category'];?>>
          </div>
          <div>
            <h3 class="rates__title"><a href="lot.php?id=<?=$value['goodsItem'];?>"><?=$value['name'];?></a></h3>
          </div>
        </td>
        <td class="rates__category">
            <?=$value['category'];?>
        </td>
        <td class="rates__timer">        
          <div class="timer timer--finishing">
               <?=date('H:i:s', strtotime($value['lotTimeRemaining']))?>
          </div>
        </td>
        <td class="rates__price">
            <?=number_format($value['cost'], 0, '.', ' ') . ' р';?>
        </td>
        <td class="rates__time">
          <?=getHumanTimeUntilRateEnd($value['timeBetting']);?>
        </td>
      </tr>  
      <?php endforeach; ?>    
    </table>
  </section>

</main>