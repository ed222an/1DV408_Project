<?php
	
require_once("./view/mainView.php");

class MainController
{
	private $mainView;
	
	public function __construct()
	{
		$this->mainView = new MainView();
	}
	
	public function doMainControl()
	{
		// Shows the instructions.
		if($this->mainView->userClickedInstructions())
		{
			return $this->mainView->showInstructions();
		}
				
		// Shows the startmenu as default.
		return $this->mainView->showMain();
	}
}

?>