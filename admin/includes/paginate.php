<?php
	
	
	class Paginate {
		
		public $current_page;
		public $items_per_page;
		public $items_total_count;
		
		
		public function __construct($page=1,$items_per_page=10,$items_total_count=0) {
			
			$this->current_page = (int)$page;
			$this->items_per_page = (int)$items_per_page;
			$this->items_total_count = (int)$items_total_count;

			
		}
		//next page by add 1
		public function next() {
			return $this->current_page + 1;
		}
		//previous page by sub 1
		public function previous() {
			return $this->current_page - 1;
		}

		//total pages ex: total_count(total photos = 15)/items per page(ex = 5)
		//returns total 15/5 = ceil(round-up);
		//from index.php
		public function total_page() {
			
			return ceil($this->items_total_count/$this->items_per_page);
		}
		//to know we have previous page or no
		public function has_previous() {
			
			return $this->previous() >= 1 ? true : false;
		}
		//to know we have next page or no
		public function has_next() {
			
			return $this->next() <= $this->total_page() ? true : false;
		}
		
		// skips amount of pages
		//ex: current_page  = 1 -1 = 0 * items per page = 1 to 5
		//ex: current_page  = 2 -1 = 1 * items per page = 5 to 10
		public function offset() {
			
			return ($this->current_page - 1 ) * $this->items_per_page;
		}
		
		




	}
?>