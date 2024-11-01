<?php ob_start(); ?>
<div id="content-editable-table" class="container">
  <table id="wpb-psc-size-table" class="table table-striped editable-table<?php echo esc_attr( isset( $_GET['post'] ) ? ' wpb-psc-table-loading' : '' ); ?>">
    <thead v-if="table.thead.length">
      <tr>
        <th v-for="(heading, index) in table.thead">
          <div class="wpb-psc-size-table-heading">
            <input type="text" v-model="table.thead[index]" />
            <div class="wpb-psc-size-table-heading-control">
               <button class="btn btn-success" type="button" v-on:click="addColumn(index)" title="<?php _e( 'Add Column', 'product-size-chart-for-woocommerce' ); ?>"><span class="dashicons dashicons-plus"></span></button>
              <button class="btn btn-danger" type="button" v-on:click="removeColumn(index)" title="<?php _e( 'Remove Column', 'product-size-chart-for-woocommerce' ); ?>"><span class="dashicons dashicons-no"></span></button>
            </div>
          </div>
        </th>
        <th></th>
      </tr>
    </thead>

    <tbody v-if="table.thead.length">
      <tr v-for="(row, bodyindex) in table.tbody">
        <td v-for="(value, index) in row">
          <input type="text" v-model="row[index]" />
        </td>
        <td  v-bind:colspan="table.thead.length + 1">
          <button class="btn btn-success" type="button" v-on:click="addRow(bodyindex)" title="<?php _e( 'Add Row', 'product-size-chart-for-woocommerce' ); ?>"><span class="dashicons dashicons-plus"></span></button>
          <button class="btn btn-danger" type="button" v-on:click="removeRow(bodyindex)" title="<?php _e( 'Remove Row', 'product-size-chart-for-woocommerce' ); ?>"><span class="dashicons dashicons-no"></span></button>
        </td>
      </tr>

    </tbody>
  </table>
</div>
<?php return ob_get_clean(); ?>`