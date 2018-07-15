<?php
	use  yii\bootstrap\Html;
?>
<div id="feedback-main">
  <div id="feedback-div">
    <?= Html::beginForm(['user/query'], 'post', ['enctype' => 'multipart/form-data']) ?>
      
      <p class="name">
			  <input name="name" id="name" type="name" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" required placeholder="Name" id="feedback-name" />
		  </p>

			<p class="email">
				<input name="email" id="email" type="email" class="validate[required,custom[email]] feedback-input" id="feedback-email" placeholder="Email" required />
			</p>

			<p class="text">
				<textarea name="comment" id="comment" type="comment" class="validate[required,length[6,500]] feedback-input" id="feedback-comment" required placeholder="If you have any query please type it here & send it to us. You can close this form by clicking queries button again."></textarea>
			</p>

			<div class="feedback-submit">
				<input type="submit" value="SEND" id="feedback-button-blue"/>
				<div class="feedback-ease"></div>
			</div>
		<?= Html::endForm(); ?>
	</div>
</div>
<button id="popup" class="feedback-button" onclick="toggle_visibility()">Queries</button>
