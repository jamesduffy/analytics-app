<div class="container">

    <div class="row">

        <div class="col-md-4">
        	
            <?php $this->load->view('admin/sidebar'); ?>

        </div>

        <div class="col-md-8">
        	<div class="inner box">
                <?=form_open('admin/create_uninteresting_client')?>
                    <input
                        id="new_uninteresting_client" name="new_uninteresting_client"
                        type="text"
                        class="form-control"
                        placeholder="Enter the client's name">
                </form>

                <hr>

                <table class="table table-striped">
                    <tr>
                        <td>Client ID</td>
                        <td>Client Name</td>
                        <td>Actions</td>
                    </tr>

                    <?php foreach($uninteresting_client as $client): ?>
                        <tr>
                            <td><?=$client['client_id']?></td>
                            <td><?=$client['client_name']?></td>
                            <td>
                                <?=anchor('admin/delete_uninteresting_client/'.$client['client_id'], 'Delete')?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </table>

        	</div>        
        </div>

    </div><!-- /.row-fluid -->

</div><!-- /.container -->