<div class="container">

    <div class="row">

        <div class="col-md-4">
        	
            <?php $this->load->view('admin/sidebar'); ?>

        </div>

        <div class="col-md-8">
        	<div class="inner box">
                <?=form_open('admin/create_uninteresting_user')?>
                    <input
                        id="new_uninteresting_user" name="new_uninteresting_user"
                        type="text"
                        class="form-control"
                        placeholder="Enter the user's id or @username">
                </form>

                <hr>

                <table class="table table-striped">
                    <tr>
                        <td>User ID</td>
                        <td>User Name</td>
                        <td>Actions</td>
                    </tr>

                    <?php foreach($uninteresting_users as $user): ?>
                        <tr>
                            <td><?=$user['user_id']?></td>
                            <td><?=anchor('profile/'.$user['username'], $user['username'])?></td>
                            <td>
                                <?=anchor('admin/delete_uninteresting_user/'.$user['user_id'], 'Delete')?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </table>

        	</div>        
        </div>

    </div><!-- /.row-fluid -->

</div><!-- /.container -->