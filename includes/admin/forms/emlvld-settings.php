<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
global $emlvld_settings, $emlvld_msg;
?>

<div class="wp-el-settings-wrap">
     <form class="wp-el-settings" id="emlvld_settings" name="emlvld_settings" method="post" action="options.php">
	     <?php settings_fields( 'emlvld_settings' ); ?>
          <div class="wp-el-settings-inner">
               <h2><?php esc_html_e('Authentication', 'emlvld');?></h2>
               <div class="wp-el-settings-top-text">
               <?php
               echo sprintf( esc_html__("In order to get your Emailable API key, sign up for an account %shere%s. We strongly suggest that you %sactivate auto-refill%s to avoid running out of credits.", 'emlvld'), "<a href='https://app.emailable.com/' target='_blank'>", "</a>", "<a href='https://app.emailable.com/account/billing#auto_refill' target='_blank'>", "</a>" ); 
               ?></div>
               <table class="form-table">
                    <tbody>
                         <tr>
                              <th scope="row"><label for="emlvld_api_key"><?php esc_html_e('API Key', 'emlvld');?></label></th>
                              <td>
                                   <input type="text" id="emlvld_api_key" class="regular-text" name="emlvld_settings[api_key]" value="<?php echo !empty($emlvld_settings['api_key']) ? esc_attr($emlvld_settings['api_key']) : ''; ?>" placeholder="<?php esc_html_e('live_XXXXXXXXXXXXXXXXXXXX', 'emlvld');?>">
                              </td>
                         </tr>
                    </tbody>
               </table>

               <h2><?php esc_html_e('Allowed States', 'emlvld');?></h2>

               <table class="form-table">
                    <tbody>
                         <tr>
                              <th scope="row"></th>
                              <td>
                                   <input type="checkbox" id="emlvld_states_deliverable" class="regular-text" name="emlvld_settings[states_deliverable]" value="1" disabled checked="checked">
                                   <label for="emlvld_states_deliverable"><?php esc_html_e('Deliverable', 'emlvld');?></label>
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"></th>
                              <td>
                                   <input type="checkbox" id="emlvld_states_risky" class="regular-text" name="emlvld_settings[states_risky]" value="1" <?php echo !empty($emlvld_settings['states_risky']) ? 'checked="checked"' : ''; ?>>
                                   <label for="emlvld_states_risky"><?php esc_html_e('Risky', 'emlvld');?></label>
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"></th>
                              <td>
                                   <input type="checkbox" id="emlvld_states_undeliverable" class="regular-text" name="emlvld_settings[states_undeliverable]" value="1" <?php echo !empty($emlvld_settings['states_undeliverable']) ? 'checked="checked"' : ''; ?>>
                                   <label for="emlvld_states_undeliverable"><?php esc_html_e('Undeliverable', 'emlvld');?></label>
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"></th>
                              <td>
                                   <input type="checkbox" id="emlvld_states_unknown" class="regular-text" name="emlvld_settings[states_unknown]" value="1" <?php echo !empty($emlvld_settings['states_unknown']) ? 'checked="checked"' : ''; ?>>
                                   <label for="emlvld_states_unknown"><?php esc_html_e('Unknown', 'emlvld');?></label>
                              </td>
                         </tr>
                    </tbody>
               </table>

               <h2><?php esc_html_e('Allowed Filters', 'emlvld');?></h2>

               <table class="form-table">
                    <tbody>
                         <tr>
                              <th scope="row"></th>
                              <td>
                                   <input type="checkbox" id="emlvld_free_email" class="regular-text" name="emlvld_settings[free_email]" value="1" <?php echo !empty($emlvld_settings['free_email']) ? 'checked="checked"' : ''; ?>>
                                   <label for="emlvld_free_email"><?php esc_html_e('Free', 'emlvld');?></label>
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"></th>
                              <td>
                                   <input type="checkbox" id="emlvld_role_email" class="regular-text" name="emlvld_settings[role_email]" value="1" <?php echo !empty($emlvld_settings['role_email']) ? 'checked="checked"' : ''; ?>>
                                   <label for="emlvld_role_email"><?php esc_html_e('Role', 'emlvld');?></label>
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"></th>
                              <td>
                                   <input type="checkbox" id="emlvld_accept_all" class="regular-text" name="emlvld_settings[accept_all]" value="1" <?php echo !empty($emlvld_settings['accept_all']) ? 'checked="checked"' : ''; ?>>
                                   <label for="emlvld_accept_all"><?php esc_html_e('Accept-All', 'emlvld');?></label>
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"></th>
                              <td>
                                   <input type="checkbox" id="emlvld_disposable_email" class="regular-text" name="emlvld_settings[disposable_email]" value="1" <?php echo !empty($emlvld_settings['disposable_email'])? 'checked="checked"' : ''; ?>>
                                   <label for="emlvld_disposable_email"><?php esc_html_e('Disposable', 'emlvld');?></label>
                              </td>
                         </tr>
                    </tbody>
               </table>

               <h2><?php esc_html_e('Custom Alert Messages', 'emlvld');?></h2>
               <div class="wp-el-settings-top-text"><?php esc_html_e('Customize the alert messages that will be shown if user type email addresses you do not want to be submitted in the form.', 'emlvld');?></div>
               <div class="wp-el-settings-top-text with-space"><b><?php esc_html_e('IMPORTANT:', 'emlvld');?></b>
               <?php echo sprintf( esc_html__("Custom messages will not be supported in some Wordpress plugins (Woocommerce, WordPress Forms, Jetpack Form, etc.) that use the %sis_email%s function for front-end validation.", 'emlvld'), "<b>", "</b>" );
               ?></div>

               <br/>
               <table class="form-table">
                    <tbody>
                         <tr>
                              <th scope="row"><?php esc_html_e('Undeliverable', 'emlvld');?></th>
                              <td>
                                   <input type="text" id="emlvld_undeliverable" class="regular-text" name="emlvld_settings[undeliverable]" value="<?php echo !empty($emlvld_settings['undeliverable']) ? esc_attr($emlvld_settings['undeliverable']) : esc_attr($emlvld_msg['undeliverable']); ?>">
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"><?php esc_html_e('Role', 'emlvld');?></th>
                              <td>
                                   <input type="text" id="emlvld_role_email_msg" class="regular-text" name="emlvld_settings[role]" value="<?php echo !empty($emlvld_settings['role']) ? esc_attr($emlvld_settings['role']) : esc_attr($emlvld_msg['role']); ?>">
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"><?php esc_html_e('Free', 'emlvld');?></th>
                              <td>
                                   <input type="text" id="emlvld_free_emails_msg" class="regular-text" name="emlvld_settings[free]" value="<?php echo !empty($emlvld_settings['free']) ? esc_attr($emlvld_settings['free']) : esc_attr($emlvld_msg['free']); ?>">
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"><?php esc_html_e('Disposable', 'emlvld');?></th>
                              <td>
                                   <input type="text" id="emlvld_disposable" class="regular-text" name="emlvld_settings[disposable]" value="<?php echo !empty($emlvld_settings['disposable']) ? esc_attr($emlvld_settings['disposable']) : esc_attr($emlvld_msg['disposable']); ?>">
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"><?php esc_html_e('Unknown', 'emlvld');?></th>
                              <td>
                                   <input type="text" id="emlvld_unknown" class="regular-text" name="emlvld_settings[unknown]" value="<?php echo !empty($emlvld_settings['unknown']) ? esc_attr($emlvld_settings['unknown']) : esc_attr($emlvld_msg['unknown']); ?>">
                              </td>
                         </tr>
                         <tr>
                              <th scope="row"><?php esc_html_e('Did You Mean', 'emlvld');?></th>
                              <td>
                                   <input type="text" id="emlvld_did_you_mean" class="regular-text" name="emlvld_settings[did_you_mean]" value="<?php echo !empty($emlvld_settings['did_you_mean']) ? esc_attr($emlvld_settings['did_you_mean']) : esc_attr($emlvld_msg['did_you_mean']); ?>">
                              </td>
                         </tr>
                    </tbody>
               </table>
               <p class="submit"><input type="submit" name="emlvld_submit" id="emlvld_submit" class="button button-primary" value="<?php esc_html_e('Save Changes', 'emlvld');?>"></p>
          </div>
     </form>
</div>