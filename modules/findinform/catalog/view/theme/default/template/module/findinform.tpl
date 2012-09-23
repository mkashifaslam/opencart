    <div class="findinform-message" style="display:none">
      <?php echo $info_message;?>
  </div>
<div class="findinform-modal info-boxie round-box glow" style="display:none">
  <p><b>Выделенный текст:</b></p>
  <BLOCKQUOTE class="findinform-sel">
  </BLOCKQUOTE>
  <form method="post" action="#">
    <p><b>В чем заключается ошибка?</b></p>
    <p><textarea rows="10" cols="34" name="text"></textarea></p>
    <p><?php echo $entry_captcha;?></p>
    <p><input type="text" name="captcha" value="<?php echo $captcha; ?>" /></p>
    <p><img src="index.php?route=modules/findinform/captcha" alt=""/></p>
    <?php if ($error_captcha) {?>
    <span class="error"><?php echo $error_captcha; ?></span>
    <?php } ?>
    <p><input type="submit" value="Отправить"/></p>
  </form>
</div>

