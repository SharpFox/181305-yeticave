<main>

  <?=$navigationMenu;?>

  <section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
    <?php foreach ($bets as $key => $value): ?>
      <tr class="rates__item">
        <td class="rates__info">
          <div class="rates__img">
            <img src=<?=$value['lotsUrl'];?> width="54" height="40" alt=<?=$value['category'];?>>
          </div>
          <div>
            <h3 class="rates__title"><a href="lot.php?id=<?=$value['lotId'];?>"><?=$value['lotName'];?></a></h3>
          </div>
        </td>
        <td class="rates__category">
            <?=$value['category'];?>
        </td>
        <td class="rates__timer">        
          <div class="timer timer--finishing">
               <?=getHumanTimeUntilRateEnd($value['endTime'])?>
          </div>
        </td>
        <td class="rates__price">
            <?=number_format($value['cost'], 0, '.', ' ') . ' р';?>
        </td>
        <td class="rates__time">
          <?=getHumanTimeOfLastRate($value['createdTime']);?>
        </td>
      </tr>  
      <?php endforeach; ?>    
    </table>
  </section>

</main>