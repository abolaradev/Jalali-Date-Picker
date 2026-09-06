Alpine.data('jalaliDatePicker',()=>({
    // Controls the visibility of the calendar.
    showCalendar : false,

    // Stores the currently selected Jalali date.
    selectedDate : '',

    // Stores today's Jalali date.
    today : '',

    // Stores colors's calendar.
    color : '',
    
    // Initializes the DatePicker state and synchronizes it with Livewire.
    init(){
        this.$wire.selectedDate = this.selectedDate
        this.color = this.$wire.get('color')
        this.today = this.$wire.get('today')
    },

    // Extracts the day number from a Jalali date string.
    getDayFromDateString() {
        let date = this.$el.value
        return parseInt(date.split('/').pop());
    },

    toPersianDigits(value) {
        return value.toString().replace(/\d/g, digit => '۰۱۲۳۴۵۶۷۸۹'[digit])
    },


    // Defines the bindings and behavior of the DatePicker input.
    input:{
        type : 'text',
        readonly : true ,
        'x-ref' : 'picker',
        'x-model' : 'selectedDate',

        ['x-on:click'](){
            this.showCalendar = !this.showCalendar
        },

        ['x-on:keydown'](event){
            event.preventDefault()
        }
    },

    // Defines the reset button behavior and its transition animations.
    resetDateButton:{
        'x-transition:enter': 'transition ease-out duration-200',
        'x-transition:enter-start': 'opacity-0 translate-x-2',
        'x-transition:enter-end': 'opacity-100 translate-x-0',
        'x-transition:leave': 'transition ease-in duration-150',
        'x-transition:leave-start': 'opacity-100 translate-x-0',
        'x-transition:leave-end': 'opacity-0 translate-x-2',

        ['x-show'](){
            return this.selectedDate != "" && this.$wire.get('showResetDateButton')
        },

        ['x-on:click'](){
            if(this.selectedDate != "") {
                this.selectedDate = ""
            }
        }
    },

    // Defines the calendar container behavior, positioning, and transitions.
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
            if(this.$wire.outsideClose){
                this.showCalendar = false
            }
        },

        // Change calendar color
        ['x-init'](){
            this.$nextTick( () => {
                this.$el.querySelectorAll('[class]').forEach(element => {
                   element.className = element.className.replaceAll('color', this.color)
                })
            })
        }
    },

    // Converts all calendar button text to Persian digits when enabled.
    grid: {
        ['x-init'](){
            if(this.$wire.get('withPersianDigits')){
                this.$nextTick(() => {
                    let buttons = this.$el.querySelectorAll('button')

                    buttons.forEach(button => {
                        button.textContent = this.toPersianDigits(button.textContent)
                    })
                })
            }
        }
    },

    // Defines the bindings for days that cannot be selected.
    unselectableDays:{
        type : 'button',
        disabled : true,
        
        ['x-text'](){
            return this.getDayFromDateString();
        },
    },

    // Defines the bindings and behavior for selectable calendar days.
    selectableDays : {
        type : 'button',
        
        ['x-text'](){
            return this.getDayFromDateString();
        },

        ['x-on:click'](){
            this.selectedDate =this.$el.value 

            if(this.$wire.get('autoClose')){
                this.showCalendar =false;
            }
        },

        // Applies the appropriate styles to the day based on today's
        // date and the currently selected date.
        [':class'](){ 
           const classes = []

           if (this.today == this.$el.value) {
                classes.push('border')
           }

           if (this.selectedDate == this.$el.value) {
                classes.push(
                    'bg-color-700',
                    'text-color-100',
                    'font-semibold',
                    'transition-colors',
                    'duration-300'
                )
           } else {
                classes.push(
                    'bg-color-100',
                    'text-color-700',
                    'hover:bg-color-200'
                )
           }

            return classes.join(' ')
        },
    }
}))