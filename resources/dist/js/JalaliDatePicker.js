document.addEventListener('alpine:init',function(){
     Alpine.data('jalaliDatePicker',()=>({
        showCalendar : false,
       
        getDayFromDateString() {
            var date = this.$el.value
            return parseInt(date.split('/').pop());
        },


        input:{
            type : 'text',
            readonly : true ,
            'x-ref' : 'picker',
           

            ['x-on:click'](){
                this.showCalendar = !this.showCalendar
            }
        },

        calendar:{
            dir : 'rtl',
            
            'x-transition:enter'       : 'transition ease-out duration-200',
            'x-transition:enter-start' : 'opacity-0 -translate-y-2',
            'x-transition:enter-end'   : 'opacity-100 translate-y-0',
            'x-transition:leave'       : 'transition ease-in duration-150',
            'x-transition:leave-start' : 'opacity-100 translate-y-0',
            'x-transition:leave-end'   : 'opacity-0 -translate-y-1',

            ['x-show'](){
                return this.showCalendar
            },

            ['x-anchor'](){
                return this.$refs.picker
            },

            ['x-on:click.outside'](){
                this.showCalendar = false
            }
        },

        unselectableDays:{
            type : 'button',
            disabled : true,
            
            ['x-text'](){
                return this.getDayFromDateString();
            },
        },

        selectableDays : {
            type : 'button',

            ['x-text'](){
                return this.getDayFromDateString();
            },

            ['x-on:click'](){
                this.$wire.selectedDate =this.$el.value 
                this.showCalendar =false;
            }
        }
    }))
})