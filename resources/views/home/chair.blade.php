@if(count($devices) > 0)
            <div v-for="(key, device) in devices">
                <div v-if="device.device_type == 'chair' ">
                    <div class="col-lg-4 col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-offset-3 col-xs-9 text-right">
                                        <div class="huge text-capitalize">@{{device.device_type}}</div>
                                        <p><br></p>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">@{{device.name}}</span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div v-for="(key, device) in devices">
                <div v-if="device.device_type == 'wekker' ">
                    <div class="col-lg-4 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-offset-3 col-xs-9 text-right">
                                        <div class="huge text-capitalize">@{{device.device_type}}</div>
                                        <p><br></p>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">@{{device.name}}</span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
@endif