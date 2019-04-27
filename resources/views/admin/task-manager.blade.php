<?php if (View::exists(config('tasks.admin.layout-master'))) { ?>
    @extends(config('tasks.layout-master'))
<?php } ?>

@section('webpage_title', 'Task Manager')

@section('webpage_header')
<h1>
    Task Queue Manager
    <button type="button" class="btn btn-primary float-right" onclick="showPageCreateModal();">
        <span class="fas fa-plus-sign"></span>
        Queue Task
    </button>
</h1>
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminHome(); ?>">
            <i class="fa fa-dashboard"></i> Home
        </a>
    </li>
    <li class="breadcrumb-item active">
        <a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminHome(); ?>">
            Task Queue
        </a>
    </li>
</ol>
@stop

@section('webpage_content')

@include('tasks::admin.shared-navigation')
@include('tasks::admin.queue-task-delete-modal')
@include('tasks::admin.queue-task-details-modal')
@include('tasks::admin.queue-task-requeue-modal')

<div class="box box-primary">
    <div class="box-header with-border">
    </div>

    <div class="box-body">
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

            <?php foreach ($tasks as $t) { ?>
                <?php
                $taskName = $t->Title;
                $createdAtTime = trim($t->CreatedAt);
                $createdAt = ($createdAtTime != "") ? 'n/a' : date('Y-m-d H:i:s', strtotime($createdAtTime));
                $updatedAtTime = trim($t->UpdatedAt);
                $updatedAt = ($updatedAtTime != "") ? 'n/a' : date('Y-m-d H:i:s', strtotime($updatedAtTime));
                ?>
                <tr>
                    <td>
                        <div style="color:#333;font-size: 14px;font-weight:bold;">
                            <?php echo $taskName; ?>
                        </div>                        
                        <div style="color:#333;font-size: 12px;font-style:italic;">
                            <?php echo $t->Alias; ?>
                        </div>
                        <div style="color:#333;font-size: 12px;font-style:italic;">
                            created. <?php echo $createdAt; ?>
                        </div>
                        <div style="color:#999;font-size: 10px;">
                            ref. <?php echo $t->Id; ?>
                        </div>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $t->Status; ?><br>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <button class="btn btn-sm btn-info" onclick="showTaskDetailsModal('<?php echo $t->Id; ?>');" title="Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-info" onclick="showTaskRequeueModal('<?php echo $t->Id; ?>');" title="Re-queue">
                            <span class="fas fa-retweet"></span>
                        </button>
                        <?php if ($t->Status == 'Deleted') { ?>
                            <button class="btn btn-sm btn-danger" onclick="showQueueTaskDeketeModal('<?php echo $t->Id; ?>');" title="Delete">
                                <i class="fas fa-minus-circle"></i>
                            </button>
                        <?php } ?>

                        <?php if ($t->Status != 'Deleted') { ?>
                            <button class="btn btn-sm btn-danger" onclick="showQueueTaskDeketeModal('<?php echo $t->Id; ?>');" title="Trash">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>

        </table>

        <!-- START: Pagination -->    
        {!! $tasks->render() !!}
        <!-- END: Pagination -->

        <br />
        <br />

    </div>
</div>

<script>
    setTimeout(function () {
        // Autorefresh
        window.location.href = window.location.href;
    }, 20000);
</script>

@stop
