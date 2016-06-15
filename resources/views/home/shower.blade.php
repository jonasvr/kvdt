<div v-for="(key, shower) in showers"> {{--foreach--}}
   <div v-if="shower.koten_id = koten_id" class="col-lg-3 col-md-6">
       <div  v-bind:class=" [panel, shower.state  == 0 ? green : red]">
           <div class="panel-heading">
               <div class="row">
                   <div class="col-xs-3">
                       <i v-bind:class=" [shower.state == 0 ? unlock : lock]" ></i>
                   </div>
                   <div class="col-xs-9 text-right">
                       <div class="huge">
                           <div v-if="shower.state == 0">Free</div>
                           <div v-else>Taken</div>
                       </div>
                       <p>shower</p>
                   </div>
               </div>
           </div>
           <a href="#">
               <div class="panel-footer">
                   <span class="pull-left">@{{ shower.name }}</span>
                   <div class="clearfix"></div>
               </div>
           </a>
       </div>
   </div>
</div>

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.6/socket.io.min.js"></script>--}}

{{--<script src="/js/shower.js"></script>--}}