<?php
require_once('function_base.php');

class regRecord
{
	public $ID; //String
	public $regTime; //String
	public $regIP; //String
	public $regMethod, $state; //Int
	public $email,$phone; //String
	public $authQuestion,$authAnswer; //String
}
class inviteRecord
{
    public $code; //String
    public $regID; //String
    public $type,$state; //Int
    public $validity; //Int
    public $creationTime,$regTime; //String
}

class DB
{
	public $OK; //Bool
	protected $defaultDB='/../db/db.dat',$dbPrefix='WiChatDD'; //String
	protected $db=''; //String
	protected $Ver=2; //Int
	protected $nowPointer;
	const timeFormat='Y/m/d,H:i:s';
	function __construct($dbFile)
	{
		$this->OK=false;
		if (!$dbFile) $dbFile=dirname(__FILE__).$this->defaultDB;
		if (!file_exists($dbFile))
		{
			$f=fopen($dbFile,'wb');
			if (!$f) return;
			flock($f,LOCK_EX);
			fwrite($f,$this->dbPrefix);
			fwrite($f,self::currentTime().chr(SERVER_ID));
			fwrite($f,chr(SERVER_ID));
			fwrite($f,chr($this->Ver));
			fwrite($f,chr(0).chr(0));
			flock($f,LOCK_UN);
			fclose($f);
		}
		else
		{
			$f=fopen($dbFile,'rb');
			if (!$f) return; 
			if (fread($f,8)!=$this->dbPrefix) return;
			fseek($f,29);
			$ver=ord(fread($f,1));
			fclose($f);
			if ($ver!=$this->Ver) return;
		}
		$this->db=$dbFile;
		$this->OK=true;
	}
	public function count() //Return:Int; <0 Indicating error
	{
		if (!$this->sync()) return -1;
		$f=fopen($this->db,'rb');
		fseek($f,30);
		$temp=fread($f,2);
		fclose($f);
		return bytesToInt($temp,2);
	}
	protected static function _count($f) //Return:Int; <0 Indicating error; Permission of 'rb' required
	{
		$p=ftell($f);
		fseek($f,30);
		$temp=fread($f,2);
		fseek($f,$p);
		return bytesToInt($temp,2);
	}
	protected static function _inc($f) //Return:Bool; Permission of 'rb+'required
	{
		$p=ftell($f);
		fseek($f,30);
		$temp=bytesToInt(fread($f,2),2);
		if ($temp<65536) $temp+=1; else return false;
		fseek($f,30);
		fwrite($f,intToBytes($temp,2));
		fseek($f,$p);
		return true;
	}
	protected static function _dec($f) //Return:Bool; Permission of 'rb+'required
	{
		$p=ftell($f);
		fseek($f,30);
		$temp=bytesToInt(fread($f,2),2);
		if ($temp>0) $temp-=1; else return false;
		fseek($f,30);
		fwrite($f,intToBytes($temp,2));
		fseek($f,$p);
		return true;
	}
	protected function sync()
	{
		return true;
	}
	protected static function update($f) //Permission of 'rb+'required
	{	
		fseek($f,8);
		fwrite($f,self::currentTime().chr(SERVER_ID));
	}
	protected static function currentTime() //Return:String
	{
		$time=gmdate(self::timeFormat);
		str_fix($time,19);
		return $time;
	}
}

class regDB extends DB
{
	protected $defaultDB='/../db/reg.dat',$dbPrefix='WiChatID';
	protected $Ver=2;
	const nullID="\0\0\0\0\0\0\0\0";
	
	private static function _locateRecord($f,$recordID) //Return: Int
	{
        if (!$f) return -1;
		fseek($f,32);
		while(true)
		{
			$tempID=fread($f,8);
			if ($recordID==$tempID) break;
			if (feof($f)) break;
			fseek($f,248,SEEK_CUR);
		}
		if ($recordID==$tempID) return ftell($f)-8; else return 0;
	}
	public function existRecord($recordID) //Return: Bool
	{
        if (!$this->sync()) return false;
		$f=fopen($this->db,'rb');
		$pos=self::_locateRecord($f,$recordID);
		fclose($f);
		if ($pos<=0) return false;
		else return true;
	}
		public function getRecord($recordID)	//Return: regRecord
	{
		if (!$this->sync()) return NULL;
		$f=fopen($this->db,'rb');
		$pos=self::_locateRecord($f,$recordID);
		if ($pos<=0) return NULL;
		fseek($f,$pos+16);
		$tempRecord=new regRecord();
		$tempRecord->ID=$recordID;
		$temp=fread($f,20); $tempRecord->regTime=substr($temp,0,19);
		$temp=fread($f,1);	$tempRecord->regMethod=ord($temp);
		$temp=fread($f,32);	$tempRecord->regIP=$temp;
		$temp=fread($f,1);	$tempRecord->state=ord($temp);
		$temp=fread($f,48);	$tempRecord->email=$temp;
		$temp=fread($f,32);	$tempRecord->phone=$temp;
		$temp=fread($f,32);	$tempRecord->authQuestion=$temp;
		$temp=fread($f,32);	$tempRecord->authAnswer=$temp;
		fclose($f);
		return $tempRecord;
	}
	
	public function setRecord($data)	//Return: Bool
	{
		if ($data==NULL) return false;
		if (!$this->sync()) return false;
		str_fix($data->ID,8);
		str_fix($data->regIP,32);
		str_fix($data->email,48);
		str_fix($data->phone,32);
		str_fix($data->authQuestion,32);
		str_fix($data->authAnswer,74);
		
		$f=fopen($this->db,'rb+');
		flock($f,LOCK_EX);
		$pos=self::_locateRecord($f,$data->ID);
		if ($pos>0)
			fseek($f,$pos+69);
		else
		{
			if (!self::_inc($f)) {flock($f,LOCK_UN); fclose($f); return false;}
			$pos=self::_locateRecord($f,self::nullID);
			if ($pos>0) fseek($f,$pos);
			else fseek($f,0,SEEK_END);
			fwrite($f,$data->ID.str_repeat(chr(0),8).self::currentTime().chr(SERVER_ID).$data->regIP.chr($data->regMethod));
		}
		fwrite($f,chr($data->state).$data->email.$data->phone.$data->authQuestion.$data->authAnswer);
		self::update($f);
		flock($f,LOCK_UN);
		fclose($f);
		return true;
	}
	public function delRecord($ID) //Return:Bool
	{
		if (!$this->sync()) return false;
		$f=fopen($this->db,'rb+');
		flock($f,LOCK_EX);
		$pos=self::_locateRecord($f,$ID);
		if ($pos<=0) return false;
		if (!self::_dec($f)) {flock($f,LOCK_UN); fclose($f); return false;}
		fseek($f,$pos);
		fwrite($f,self::nullID);
		fseek($f,8,SEEK_CUR);
		fwrite($f,self::currentTime().chr(SERVER_ID).str_repeat(chr(0),220));
		self::update($f);
		flock($f,LOCK_UN);
		fclose($f); 
		return true;
	}
}


class inviteDB extends DB
{
	protected $defaultDB='/../db/invitation.dat',$dbPrefix='WiChatVD';
	protected $Ver=2;
	const nullID="\0\0\0\0\0\0\0\0";
	
	private static function _locateRecord($f,$recordID) //Return: Int
	{
        if (!$f) return -1;
		fseek($f,32);
		while(true)
		{
			$tempID=fread($f,8);
			if ($recordID==$tempID) break;
			if (feof($f)) break;
			fseek($f,56,SEEK_CUR);
		}
		if ($recordID==$tempID) return ftell($f)-8; else return 0;
	}
	public function existRecord($code) //Return: Bool
	{
        if (!$this->sync()) return false;
		$f=fopen($this->db,'rb');
		$pos=self::_locateRecord($f,$code);
		fclose($f);
		if ($pos<=0) return false;
		else return true;
	}
	public function getRecord($code)	//Return: regRecord
	{
		if (!$this->sync()) return NULL;
		$f=fopen($this->db,'rb');
		$pos=self::_locateRecord($f,$code);
		if ($pos<=0) return NULL;
		fseek($f,$pos+8);
		$tempRecord=new regRecord();
		$tempRecord->code=$code;
		$temp=fread($f,8);	$tempRecord->regID=$temp;
		$temp=fread($f,1);	$tempRecord->state=ord($temp);
		$temp=fread($f,1);	$tempRecord->type=ord($temp);
		$temp=fread($f,2);	$tempRecord->validity=bytesToInt($temp,2);
		$temp=fread($f,20); $tempRecord->creationTime=substr($temp,0,19);
		$temp=fread($f,20); $tempRecord->regTime=substr($temp,0,19);
		fclose($f);
		return $tempRecord;
	}	
	public function setRecord($data)	//Return: Bool
	{
		if ($data==NULL) return false;
		if (!$this->sync()) return false;
		str_fix($data->code,8);
		str_fix($data->regID,8);
		str_fix($data->regTime,19);
		
		$f=fopen($this->db,'rb+');
		flock($f,LOCK_EX);
		$pos=self::_locateRecord($f,$data->code);
		if ($pos>0)
			fseek($f,$pos+8);
		else
		{
			if (!self::_inc($f)) {flock($f,LOCK_UN); fclose($f); return false;}
			$pos=self::_locateRecord($f,self::nullID);
			if ($pos>0) fseek($f,$pos);
			else fseek($f,0,SEEK_END);
			fwrite($f,$data->code.str_repeat(chr(0),12).self::currentTime().chr(SERVER_ID).str_repeat(chr(0),24));
			fseek($f,-56,SEEK_CUR);
		}
		fwrite($f,$data->regID.chr($data->state).chr($data->type).intToBytes($data->validity,2));
		fseek($f,20,SEEK_CUR);
		fwrite($f,$data->regTime.chr(SERVER_ID));
		self::update($f);
		flock($f,LOCK_UN);
		fclose($f);
		return true;
	}
	public function delRecord($code) //Return:Bool
	{
		if (!checkID($ID)) return false;
		if (!$this->sync()) return false;
		$f=fopen($this->db,'rb+');
		flock($f,LOCK_EX);
		$pos=self::_locateRecord($f,$ID);
		if ($pos<=0) return false;
		if (!self::_dec($f)) {flock($f,LOCK_UN); fclose($f); return false;}
		fseek($f,$pos);
		fwrite($f,self::nullID.str_repeat(chr(0),12));
		fwrite($f,self::currentTime().chr(SERVER_ID).str_repeat(chr(0),24));
		self::update($f);
		flock($f,LOCK_UN);
		fclose($f);
		return true;
	}
}
?>
