<?php
/**
* cakephp-github-datasource - A datasource for getting Github activity
* @author Gareth Griffiths (aka synbyte) 2013
* @link http://synbyte.com
* @link http://github.com/synbyte
**/
App::uses('HttpSocket', 'Network/Http');
App::import('Xml', 'String', 'Core');

class GithubSource extends DataSource
{
	public function __construct($config)
	{
		parent::__construct($config);
		$this->Http = new HttpSocket();
	}

	public function listSources($data=null) {
		return null;
	}

	public function read(Model $model, $queryData = array())
	{
	
		if(isset($queryData['limit'])?$this->config['limit']=$queryData['limit']:null);
	
		$items = Cache::read('github_activity');
		if(!$items)
		{
			$items = json_decode($this->Http->get('https://api.github.com/users/'.$this->config['username'].'/events',$this->config),true);

			$i=0;
			$array=array();
			foreach ($items as $item) 
			{
				if($i==$this->config['limit'])
				{
					break;
				}

				switch($item['type']) {
					case 'WatchEvent':
						$array[]=array(
							'text'=>'Starred',
							'name'=>$item['repo']['name'],
							'url'=>'http://github.com/'.$item['repo']['name'],
							'created'=>$item['created_at']
						);
						break;
					case 'FollowEvent':
						$array[]=array(
							'text'=>'Followed',
							'name'=>$item['payload']['target']['login'],
							'url'=>$item['payload']['target']['html_url'],
							'created'=>$item['created_at']
						);
						break;
					case 'ForkEvent':
						$array[]=array(
							'text'=>'Forked',
							'name'=>$item['repo']['name'],
							'url'=>'http://github.com/'.$item['repo']['name'],
							'created'=>$item['created_at']
						);
						break;
					case 'PushEvent':
						$array[]=array(
							'text'=>'Pushed to',
							'name'=>$item['repo']['name'],
							'url'=>'http://github.com/'.$item['repo']['name'],
							'created'=>$item['created_at']
						);
						break;
					case 'CreateEvent':
						$array[]=array(
							'text'=>'Created',
							'name'=>$item['repo']['name'],
							'url'=>'http://github.com/'.$item['repo']['name'],
							'created'=>$item['created_at']
						);
						break;
				}
				$i++;
			}
			Cache::write('github_activity', $array);
		}
		return($array);
	}
}