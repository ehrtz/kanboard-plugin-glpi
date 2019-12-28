<?php

namespace Kanboard\Plugin\Glpi\ExternalTask;

use Kanboard\Core\ExternalTask\ExternalTaskInterface;

class GlpiTask implements ExternalTaskInterface
{
    // Validation STATUS
    const VALIDATION_NONE      = 1;    // not subject for approval
    const VALIDATION_WAITING   = 2;    // waiting for approval
    const VALIDATION_ACCEPTED  = 3;    // granted
    const VALIDATION_REFUSED   = 4;    // refused

    #GLPI Statuses
    const INCOMING      = 1;    // new
    const ASSIGNED      = 2;    // assign
    const PLANNED       = 3;    // plan
    const WAITING       = 4;    // waiting
    const SOLVED        = 5;    // solved
    const CLOSED        = 6;    // closed
    const ACCEPTED      = 7;    // accepted
    const OBSERVED      = 8;    // observe
    const EVALUATION    = 9;    // evaluation
    const APPROVAL      = 10;   // approbation
    const TEST          = 11;   // test
    const QUALIFICATION = 12;   // qualification

    # Urgency, Impact and Priotity
    # Standard Priotity
    const MAJOR     = 6;
    const VERY_HIGH = 5;
    const HIGH      = 4;
    const MEDIUM    = 3;
    const LOW       = 2;
    const VERY_LOW  = 1;

    # Non Standard
    const ALL                     = 0;
    const AT_VERY_LEAST_VERY_LOW  = -1;
    const AT_LEAST_LOW            = -2;
    const AT_LEAST_MEDIUM         = -3;
    const AT_LEAST_HIGH           = -4;
    const AT_VERY_LEAST_VERY_HIGH = -5;

    # Request type
    const INCIDENT_TYPE = 1;    // incident
    const DEMAND_TYPE   = 2;    // request

    protected $uri;
    protected $ticket;

    public function __construct($uri, $ticket)
    {
        $this->uri = $uri;
        $this->ticket = $ticket;
    }

    /**
     * Return Uniform Resource Identifier for the task
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Return the ticket id
     *
     * @return int
     */
    public function getTicketId()
    {
        return $this->ticket['id'];
    }

    /**
     * Return the ticket details
     *
     * @return array
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Return a dict to populate the task form
     *
     * @return array
     */
    public function getFormValues()
    {
        $title = sprintf('GLPI %d %s', $this->ticket['id'], $this->ticket['name']);
        return array(
            'title' => $title,
            'description' => $this->ticket['content'],
            'reference' => $this->ticket['id'],
            'priority' => $this->ticket['priority'],
        );
    }

    /**
    * Get ITIL Ticket Type Name
    *
    * @param integer $value ticket type ID
    * @return string
    **/
    public function getTicketType($value)
    {
        switch ($value) {
            case 1:
                return t('Incident');
            case 2:
                return t('Request');
            default:
                return $value;
        }
    }

    /**
    * Get ITIL Status Name
    *
    * @param integer $value status ID
    * @return string
    **/
    public function getStatusName($value)
    {
        switch ($value) {
            case self::INCOMING :
                return t('Incomming');
            case self::ASSIGNED :
                return t('Procsessing (Assigned)');
            case self::PLANNED :
                return t('Procsessing (planned)');
            case self::WAITING :
                return t('Waiting');
            case self::SOLVED :
                return t('Solved');
            case self::CLOSED :
                return t('Closed');
            case self::ACCEPTED :
                return t('Granted');
            case self::OBSERVED :
                return t('Observed');
            case self::EVALUATION :
                return t('For evaluation');
            case self::APPROVAL :
                return t('For approval');
            case self::TEST :
                return t('Testing');
            case self::QUALIFICATION :
                return t('For qualification');
            default:
                return $value;
        }
    }

    /**
    * Get ITIL Global Validation Name
    *
    * @param integer $value validation ID
    * @return string
    **/
    public function getValidationName($value)
    {
        switch ($value) {
            case self::VALIDATION_NONE :
                return t('Not subject for approval');
            case self::VALIDATION_WAITING :
                return t('Waiting for approval');
            case self::VALIDATION_ACCEPTED :
                return t('Granted');
            case self::VALIDATION_REFUSED :
                return t('Refused');
            default:
                return $value;
        }
    }

    /**
    * Get ITIL Urgency Name
    *
    * @param integer $value urgency ID
    * @return string
    **/
    public function getUrgencyName($value)
    {
        switch ($value) {
            case 5 :
                return t('Very high');
            case 4 :
                return t('High');
            case 3 :
                return t('Medium');
            case 2 :
                return t('Low');
            case 1 :
                return t('Very low');

            // No standard one :
            case 0 :
                return t('All');
            case -1 :
                return t('At least very low');
            case -2 :
                return t('At least low');
            case -3 :
                return t('At least medium');
            case -4 :
                return t('At least high');
            case -5 :
                return t('At least very high');
            default :
                // Return $value if not define
                return $value;
      }
    }

    /**
    * Get ITIL Impact Name
    *
    * @param integer $value impact ID
    * @return string
    **/
    public function getImpactName($value)
    {
        switch ($value) {
         case 5 :
            return t('Very high');
         case 4 :
            return t('High');
         case 3 :
            return t('Medium');
         case 2 :
            return t('Low');
         case 1 :
            return t('Very low');

         // No standard one :
         case 0 :
            return t('All');
         case -1 :
            return t('At least very low');
         case -2 :
            return t('At least low');
         case -3 :
            return t('At least medium');
         case -4 :
            return t('At least high');
         case -5 :
            return t('At least very high');
         default :
            // Return $value if not define
            return $value;
      }
    }

    /**
    * Get ITIL Priority Name
    *
    * @param integer $value priority ID
    * @return string
    **/
    public function getPriorityName($value)
    {
        switch ($value) {
            case 6 :
                return t('Major');
            case 5 :
                return t('Very high');
            case 4 :
                return t('High');
            case 3 :
                return t('Medium');
            case 2 :
                return t('Low');
            case 1 :
                return t('Very low');

            // No standard one :
            case 0 :
                return t('All');
            case -1 :
                return t('At least very low');
            case -2 :
                return t('At least low');
            case -3 :
                return t('At least medium');
            case -4 :
                return t('At least high');
            case -5 :
                return t('At least very high');
            default :
                // Return $value if not define
                return $value;
      }
    }

}
