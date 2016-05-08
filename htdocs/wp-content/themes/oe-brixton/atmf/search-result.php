
        <div class="row control-area">
            <div class="col-md-2 pull-right" >
                    <select class="form-control"  ng-model="postsPerPage">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
            </div>
            <div class="col-md-4 pull-right">
                <div class="row">
                    <div class="col-md-8">
                        <select class=" form-control pull-left" ng-init="postOrder.order = 'reverse'" ng-model="postOrder.type"  >
                            <option value="">Sort By</option>
                            <option ng-repeat="(key ,value) in sortData" value="{{value.label}}">{{value.text}}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <a href="#" ng-click="postOrder.order = false " class="btn btn-xs btn-praimary pull-right">
                            <span class="glyphicon glyphicon-arrow-down"></span>
                        </a>

                        <a href="#" ng-click="postOrder.order = 'reverse' " class="btn btn-xs btn-praimary pull-right">
                            <span class="glyphicon glyphicon-arrow-up"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    	<div class="clearfix"></div>
		<div dir-paginate="post in posts | itemsPerPage: postsPerPage | filter: QuickSearch | orderBy:postOrder.type:postOrder.order">
               <div ng-if="postView == 'list' " class="row post-item">
                    <div class="col-md-12">
                        <h2><a href="{{ post.post_permalink }}" ng-bind="post.post_title"></a></h2>
                        <p ng-bind-html="post.post_content | html"></p>
                        <div class="row listing-details">
                         <div ng-if="post.post_opening_times">
         					<div class="field">
         						<div class="label">
         							Opening Times:
         						</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_opening_times | html"></p>
         						</div>
         				     </div>
                         </div>
                         <div ng-if="post.post_phone">
         					<div class="field">
         						<div class="label">
         						Phone:
         						</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_phone | html"></p>
         						</div>
         					</div>
                         </div>
                         <div ng-if="post.post_email">
         					<div class="field">
         						<div class="label">
         						Email:
         						</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_email | html"></p>
         						</div>
         					</div>
         				</div>
                         <div ng-if="post.post_address">
         					<div class="field">
         						<div class="label">
         							Address:
         						</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_address | html"></p>
         						</div>
         					</div>
                         </div>
                         <div ng-if="post.post_area">
         						<div class="field">
         						<div class="label">
         							Area:
         						</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_area | html"></p>
         						</div>
         					</div>
                         </div>
                         <div ng-if="post.post_website">
         					<div class="field">
         						<div class="label">
         							Website:
         						</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_website | html"></p>
         						</div>
         					</div>
         				</div>
                              <div ng-if="post.post_transport">
         					<div class="field">
         						<div class="label">Transport:</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_transport | html"></p>
         						</div>
                              </div>
         					</div>
                         <div ng-if="post.post_eligible">
         					<div class="field">
         						<div class="label">Who is eligigle:</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_eligible | html"></p>
         						</div>
         					</div>
                         </div>
                         <div ng-if="post.post_contactby">
         					<div class="field">
         						<div class="label">Contact By:</div>
         						<div class="content">
                                        <p ng-bind-html="post.post_contactby | html"></p>
                                   </div>
         						</div>
         				</div>
                         <div ng-if="post.post_contact_name">
                              <div class="field">
                              <div class="label">Contact Name:</div>
                              <div class="content">
                              <p ng-bind-html="post.post_contact_name | html"></p>
                              </div>
                              </div>
                         </div>
                    </div>
               </div>
		</div>
          </div>
		<div class="loading" ng-show="loading"><i></i><i></i><i></i></div>
		<alert style="margin-top:200px;" type="danger" ng-show="( posts | filter:QuickSearch).length==0">
            Sorry No Result Found
		</alert>
		<div class="clearfix"></div>
		<!-- Start pagination  -->
		<dir-pagination-controls boundary-links="true" class="pull-right" on-page-change="pageChangeHandler(newPageNumber)" template-url="<?php  echo UOU_ATMF_URL.'/assets/js/vendor/angular-utils-pagination/dirPagination.tpl.html';  ?>"></dir-pagination-controls>
		<!-- End pagination  -->
