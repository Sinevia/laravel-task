<?php if (View::exists(config('tasks.admin.layout-master'))) { ?>
    @extends(config('tasks.layout-master'))
<?php } ?>

@section('webpage_title', 'Queue Manager')

@section('webpage_header')
<h1>
    Queue Manager
    <button type="button" class="btn btn-primary float-right" onclick="showPageCreateModal();">
        <span class="fas fa-plus-sign"></span>
        Enqueue Task
    </button>
</h1>
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminHome(); ?>">
            <i class="fa fa-dashboard"></i> Home
        </a>
    </li>
    <li class="breadcrumb-item active">
        <a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminQueueManager(); ?>">
            Queue Manager
        </a>
    </li>
</ol>
@stop

@section('webpage_content')

@include('tasks::admin.shared-navigation')
@include('tasks::admin.queue-task-delete-modal')
@include('tasks::admin.queue-task-details-modal')
@include('tasks::admin.queue-task-parameters-modal')
@include('tasks::admin.queue-task-requeue-modal')

<div class="box box-primary">
    <div class="box-header with-border">
    </div>

    <div class="box-body">
        <!-- TODO
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?php if ($view == '') { ?>active<?php } ?>" href="?view=all">
                    Live
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php if ($view == 'trash') { ?>active<?php } ?>">
                    Trash
                </a>
            </li>
        </ul>
        -->

        <table id="table_articles" class="table table-striped" style="margin-top:10px;">
            <tr>
                <th style="text-align:center;">
                    <a href="?cmd=pages-manager&amp;by=Title&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                        Name&nbsp;<?php
                        if ($orderby === 'Title') {
                            if ($sort == 'asc') {
                                ?>&#8595;<?php } else { ?>&#8593;<?php
                            }
                        }
                        ?>
                    </a>,
                    <a href="?cmd=pages-manager&amp;by=Alias&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                        Alias&nbsp;<?php
                        if ($orderby === 'Alias') {
                            if ($sort == 'asc') {
                                ?>&#8595;<?php } else { ?>&#8593;<?php
                            }
                        }
                        ?>
                    </a>,
                    <a href="?cmd=pages-manager&amp;by=id&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                        ID&nbsp;<?php
                        if ($orderby === 'Id') {
                            if ($sort == 'asc') {
                                ?>&#8595;<?php } else { ?>&#8593;<?php
                            }
                        }
                        ?>
                    </a>
                </th>
                <th>
                    Start Time
                </th>
                <th>
                    End Time
                </th>
                <th>
                    Elapsed Time
                </th>
                <th style="text-align:center;width:100px;">
                    <a href="?cmd=pages-manager&amp;by=Status&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                        Status&nbsp;<?php
                        if ($orderby === 'Status') {
                            if ($sort == 'asc') {
                                ?>&#8595;<?php } else { ?>&#8593;<?php
                            }
                        }
                        ?>
                    </a>
                </th>
                <th style="text-align:center;width:160px;">Action</th>
            </tr>

            <?php foreach ($queuedTasks as $qt) { ?>
                <?php
                $taskName = is_null($qt->task) ? 'n/a' : $qt->task->Title;
                $createdAtTime = trim($qt->CreatedAt);
                $startedAtTime = trim($qt->StartedAt);
                $completedAtTime = trim($qt->CompletedAt);
                $elapsedTime = 'n/a';
                if ($startedAtTime != "" AND $startedAtTime != "") {
                    $elapsedTime = strtotime($completedAtTime) - strtotime($startedAtTime);
                }
                if ($completedAtTime != "") {
                    $completedAt = date('H:i:s', strtotime($completedAtTime));
                } else {
                    $completedAt = 'n/a';
                }
                if ($startedAtTime != "") {
                    $startedAt = date('H:i:s', strtotime($startedAtTime));
                } else {
                    $startedAt = 'n/a';
                }
                if ($createdAtTime != "") {
                    $createdAt = date('Y-m-d H:i:s', strtotime($createdAtTime));
                } else {
                    $createdAt = 'n/a';
                }
                ?>
                <tr>
                    <td>
                        <div style="color:#333;font-size: 14px;font-weight:bold;">
                            <?php echo $taskName; ?>
                        </div>                        
                        <div style="color:#333;font-size: 12px;font-style:italic;">
                            <?php echo $qt->Alias; ?>
                        </div>
                        <div style="color:#333;font-size: 12px;font-style:italic;">
                            created. <?php echo $createdAt; ?>
                        </div>
                        <div style="color:#999;font-size: 10px;">
                            ref. <?php echo $qt->Id; ?>
                        </div>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $startedAt; ?>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $completedAt; ?>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $elapsedTime; ?><br>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $qt->Status; ?><br>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <button class="btn btn-sm btn-info" onclick="showTaskParametersModal('<?php echo $qt->Id; ?>');" title="Parameters">
                            Parameters
                        </button>
                        <button class="btn btn-sm btn-info" onclick="showTaskDetailsModal('<?php echo $qt->Id; ?>');" title="Details">
                            Details
                        </button>
                        <button class="btn btn-sm btn-info" onclick="showTaskRequeueModal('<?php echo $qt->Id; ?>');" title="Re-queue">
                            Requeue
                        </button>
                        <?php if ($qt->Status == 'Deleted') { ?>
                            <button class="btn btn-sm btn-danger" onclick="showQueueTaskDeketeModal('<?php echo $qt->Id; ?>');" title="Delete">
                                Delete
                            </button>
                        <?php } ?>

                        <?php if ($qt->Status != 'Deleted') { ?>
                            <button class="btn btn-sm btn-danger" onclick="showQueueTaskDeketeModal('<?php echo $qt->Id; ?>');" title="Trash">
                                Trash
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>

        </table>

        <!-- START: Pagination -->    
        {!! $queuedTasks->render() !!}
        <!-- END: Pagination -->
        
        <br />
        <br />

    </div>
</div>

<script>
    window.autoreload = true;
    setTimeout(function () {
        if (window.autoreload == false) {
            return;
        }
        // Autorefresh
        window.location.href = window.location.href;
    }, 20000);
</script>

@stop
