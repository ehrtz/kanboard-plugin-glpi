<?php
    $ticket = $external_task->getTicket();
    $t_actor = $ticket['actor'];
?>
<details class="accordion-section" <?= 'open' ?> >
    <summary class="accordion-title"><?= t('GLPI Ticket Details') ?></summary>
    <div class="accordion-content glpi-ticket-details" id="glpi-ticket-details">
        <table>
            <tbody>
                <th colspan="4" style="text-align:center;">
                    <?= t('Ticket - Id') . ' ' . sprintf('<a href="%s">%s</a>', $external_task->getUri(), $external_task->getTicketId()) ?>
                </th>
                <tr>
                    <th width="13%"><?= t('Opening date') ?></th>
                    <td width="37%"><?= $ticket['date'] ?></td>
                    <th width="13%"><?= t('By') ?></th>
                    <td width="37%"><?= $ticket['users_id_recipient'] ?></td>
                </tr>

                <tr>
                    <th width="13%"><?= t('Last update') ?></th>
                    <td colspan="3"><?= $ticket['date_mod'] . '          ' . t('by') . ' ' .  $ticket['users_id_lastupdater'] ?></th>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <th width="13%"><?= t('Type') ?></th>
                    <td width="37%"><?= $external_task->getTicketType($ticket['type']) ?></td>
                    <th width="13%"><?= t('Category') ?></th>
                    <td width="37%"><?= $ticket['itilcategories_id'] ?></td>
                </tr>

                <tr>
                    <th width="13%"><?= t('Status') ?></th>
                    <td width="29%"><?= $external_task->getStatusName($ticket['status']) ?></td>
                    <th width="13%"><?= t('Request source') ?></th>
                    <td width="29%"><?= $ticket['requesttypes_id'] ?></td>
                </tr>

                <tr>
                    <th width="13%"><?= t('Urgency') ?></th>
                    <td width="29%"><?= $external_task->getUrgencyName($ticket['urgency']) ?></td>
                    <th width="13%"><?= t('Approval') ?></th>
                    <td width="29%"><?= $external_task->getValidationName($ticket['global_validation']) ?></td>
                </tr>

                <tr>
                    <th width="13%"><?= t('Impact') ?></th>
                    <td width="29%"><?= $external_task->getImpactName($ticket['impact']) ?></td>
                    <th width="13%"><?= t('Location') ?></th>
                    <td width="29%"><?= $ticket['locations_id'] ?></td>
                </tr>

                <tr>
                    <th width="13%"><?= t('Priority') ?></th>
                    <td width="29%"><?= $external_task->getPriorityName($ticket['priority']) ?></td>
                    <th width="13%"></th>
                    <td width="29%"></td>
                </tr>
             </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <th width="13%" rowspan="2"><?= t('Actor') ?></th>
                    <th width="29%" style="text-align:center;"><?= t('Requester') ?></th>
                    <th width="29%" style="text-align:center;"><?= t('Watcher') ?></th>
                    <th width="29%" style="text-align:center;"><?= t('Assigned to') ?></th>
                </tr>
                <tr>
                    <td width="29%">
                    <?php
                        # actor type
                        # 1 = Requester
                        foreach ( $t_actor as $actor) {
                            if ($actor[type] == 1) {
                                echo '<i class="fa fa-user" title="Requester user"></i>&nbsp', $actor['users_id'], '<br>';
                            }
                        }
                    ?>
                    </td>
                    <td width="29%">
                    <?php
                        # actor type
                        # 3 = Watcher
                        foreach ( $t_actor as $actor) {
                            if ($actor[type] == 3) {
                                echo '<i class="fa fa-user" title="Watcher user"></i>&nbsp', $actor['users_id'], '<br>';
                            }
                        }
                    ?>
                    </td>
                    <td width="29%">
                    <?php
                        # actor type
                        # 2 = Assigned to
                        foreach ( $t_actor as $actor) {
                            if ($actor[type] == 2) {
                                echo '<i class="fa fa-user" title="Technician user"></i>&nbsp', $actor['users_id'], '<br>';
                            }
                        }
                    ?>
                    </td>
                </tr>
             </tbody>
        </table>
    </div>
</details>
