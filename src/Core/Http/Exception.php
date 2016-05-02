<?php
namespace Core\Http;

class Exception extends \Exception
{
    /**
     * @var integer
     */
    protected $status;

    /**
     * Constructor
     *
     * @param integer    $status
     * @param string     $message
     * @param \Exception $previous
     * @param integer    $code
     */
    public function __construct(
        $status,
        $message = null,
        \Exception $previous = null,
        $code = 0
    )
    {
        $this->status = $status;
        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * Converts the Exception to Array
     * @param bool $withStackTrace
     * @return array
     */
    public function toArray($withStackTrace = false)
    {
        $data = [
            'ok'      => false,
            'error'   => [
                'code' => $this->getStatusCode(),
                'message' => $this->getMessage(),
            ],
        ];
        if ($withStackTrace) {
            $data['trace'] = $this->getTrace();
        }
        return $data;
    }

    /**
     * Converts the Exception to JSON
     * @param bool $withStackTrace
     * @return string
     */
    public function toJson($withStackTrace = false)
    {
        return json_encode($this->toArray($withStackTrace));
    }
}