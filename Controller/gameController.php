<?php
	
	require_once("./view/gameView.php");
	require_once("./model/gameModel.php");
	
	class GameController
	{
		private $gameView;
		private $gameModel;
		
		public function __construct()
		{
			$this->gameModel = new GameModel();
			$this->gameView = new GameView($this->gameModel);
		}
		
		public function doGameControl()
		{
			return $this->gameView->showContents();
		}
	}
?>