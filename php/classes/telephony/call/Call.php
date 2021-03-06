<?

class Call extends WritablePersistence implements Media
{
    public static $UPLOAD_RELATIVE_PATH = "call/";

    protected $id_device, $_id, $contact, $phone_number, $type, $date, $duration, $record, $warning, $added_at;

    /**
     * Call constructor.
     * @param $id_device
     * @param $_id
     * @param $contact
     * @param $phone_number
     * @param $type
     * @param $date
     * @param $duration
     * @param $record
     * @param $added_at
     */
    public function __construct($id_device = null, $_id = null, $contact = null, $phone_number = null, $type = null, $date = null, $duration = null, $record = null, $warning = null, $added_at = null)
    {
        parent::__construct();
        $this->id_device = $id_device;
        $this->_id = $_id;
        $this->contact = $contact;
        $this->phone_number = $phone_number;
        $this->type = $type;
        $this->date = $date;
        $this->duration = $duration;
        $this->record = $record;
        $this->warning = $warning;
        $this->added_at = $added_at;
    }

    /**
     * @return string
     */
    public static function getUPLOADRELATIVEPATH()
    {
        return self::$UPLOAD_RELATIVE_PATH;
    }

    /**
     * @return null
     */
    public function getIdDevice()
    {
        return $this->id_device;
    }

    /**
     * @param null $id_device
     */
    public function setIdDevice($id_device)
    {
        $this->id_device = $id_device;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return null
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param null $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return null
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param null $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param null $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param null $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return null
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * @param null $record
     */
    public function setRecord($record)
    {
        $this->record = $record;
    }

    /**
     * @return null
     */
    public function getWarning()
    {
        return $this->warning;
    }

    /**
     * @param null $warning
     */
    public function setWarning($warning)
    {
        $this->warning = $warning;
    }

    /**
     * @return null
     */
    public function getAddedAt()
    {
        return $this->added_at;
    }

    /**
     * @param null $added_at
     */
    public function setAddedAt($added_at)
    {
        $this->added_at = $added_at;
    }

    public function delete()
    {
        $file = Api::$MEDIA_FILES_UPLOAD_PATH . "{$this->getIdDevice()}/" . Call::$UPLOAD_RELATIVE_PATH . $this->getRecord();
        if (is_file($file)) unlink($file);
        return parent::delete(); // TODO: Change the autogenerated stub
    }


    public static function isCall($id_device, $id_call)
    {
        $id_call = abs(intval($id_call));
        $id_device = abs(intval($id_device));
        if (!$id_call or !$id_device) return false;
        return self::find(new Call($id_call, $id_call));
    }

    public static function getCall($id_device, $id_call)
    {
        $Call = new Call($id_device, $id_call);
        $Call->load();
        return $Call;
    }

    public static function bindTo(Device $device, Row $data)
    {
        $Call = new Call($device->getId());
        if (!$data->indexExists("_id")) throw new UserFriendlyException("_id is missing", "Failed", 1004);
        $Call->setId($data->getColumn("_id")->getValue());
        if (!$data->indexExists("contact")) throw new UserFriendlyException("contact is missing", "Failed", 1004);
        $Call->setContact($data->getColumn("contact")->getValue());
        if (!$data->indexExists("phone_number")) throw new UserFriendlyException("phone_number is missing", "Failed", 1004);
        $Call->setPhoneNumber($data->getColumn("phone_number")->getValue());
        if (!$data->indexExists("type")) throw new UserFriendlyException("type is missing", "Failed", 1004);
        $Call->setType($data->getColumn("type")->getValue());
        if (!$data->indexExists("date")) throw new UserFriendlyException("date is missing", "Failed", 1004);
        $Call->setDate($data->getColumn("date")->getValue());
        if (!$data->indexExists("duration")) throw new UserFriendlyException("duration is missing", "Failed", 1004);
        $Call->setDuration($data->getColumn("duration")->getValue());

        if (!file_exists($_FILES['record']['tmp_name']) || !is_uploaded_file($_FILES['record']['tmp_name'])) throw new UserFriendlyException("record file missing", "Failed", 1004);
        $file_name = "{$_FILES['record']['name']}.mp4";
        $path = Api::$MEDIA_FILES_UPLOAD_PATH . "{$device->getId()}/" . Call::$UPLOAD_RELATIVE_PATH;
        if (!is_dir($path)) mkdir($path, 0777, true);
        @move_uploaded_file($_FILES['record']['tmp_name'], $path . $file_name);
        if (!is_file($path . $file_name)) throw new UserFriendlyException("uploading file failed", "FAILED", 1005);
        $Call->setRecord($file_name);

        self::insert($Call);
    }


}