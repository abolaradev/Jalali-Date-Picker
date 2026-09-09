Alpine.data('jalaliDatePicker',()=>({

    // Controls the visibility of the calendar.
    showCalendar : false,

    // Stores the currently selected Jalali date.
    selectedDate : '',

    // Stores the currently selected month.
    month : '',

    // Stores the currently selected year.
    year : '',

    // Stores colors's calendar.
    color : '',
    
    // Navigation panel display status
    showDateNavigationPanel : false,

    // Display status of the year or month selection in the navigation panel.
    showPicker :{
        month : false,
        year : false,
    },


    // Initializes the DatePicker state and synchronizes it with Livewire.
    init(){
        this.color = this.$wire.get('color')
    },

    // Extracts the day number from a Jalali date string.
    getDayFromDateString() {
        let date = this.$el.value
        return parseInt(date.split('/').pop());
    },

    // It converts the English numerals in the calendar to Persian.
    toPersianDigits(value) {
        return value.toString().replace(/\d/g, digit => '۰۱۲۳۴۵۶۷۸۹'[digit])
    },

    // Color coordination of calendar elements
    changeCalendarColor(classes){
        return classes.replace(/color/gi, this.color)
    },

    // Closes the navigation panel.
    closeDateNavigationPanel()
    {
        this.showDateNavigationPanel = false 
        this.showPicker.month= false
        this.showPicker.year = false
    },

    input : {
        // Defines the bindings and behavior of the DatePicker input.
        input:{
            type : 'text',
            readonly : true ,
            'x-ref' : 'picker',
            'x-model' : 'selectedDate',

            ['x-on:click'](){
                this.showCalendar = !this.showCalendar
                this.showDateNavigationPanel = false
            },

            ['x-on:keydown'](event){
                event.preventDefault()
            }
        },

         // Defines the reset button behavior and its transition animations.
        reset:{
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

        ['x-anchor.bottom-start'](){
            return this.$refs.picker
        },

        ['x-on:click.outside'](){
            if(this.$wire.closeOnOutsideClick){
                this.showCalendar = false
                this.showDateNavigationPanel = false
            }
        },
        
         // Converts all calendar button text to Persian digits when enabled.
        ['x-init'](){
            if(this.$wire.get('withPersianDigits')){
                this.$nextTick(() => {
                    const walker = document.createTreeWalker(
                        this.$el,
                        NodeFilter.SHOW_TEXT
                    )

                    let node

                    while (node = walker.nextNode()) {
                        node.nodeValue = this.toPersianDigits(node.nodeValue)
                    }
                })
            }
        },

        ['x-effect'](){
            this.$wire.selectedDate = this.selectedDate
            this.month = this.$wire.month
            this.year = this.$wire.year
        },

        // The Navigation Panel settings are determined.
        dateNavigationPanel :{

            ['x-show'](){
                return this.showDateNavigationPanel
            },

            'x-transition:enter': 'transition ease-out duration-200',
            'x-transition:enter-start' : 'opacity-0 -translate-y-2 scale-95',
            'x-transition:enter-end' : 'opacity-100 translate-y-0 scale-100',
            'x-transition:leave' : 'transition ease-in duration-150',
            'x-transition:leave-start' : 'opacity-100 translate-y-0 scale-100',
            'x-transition:leave-end' : 'opacity-0 -translate-y-1 scale-95',

            title:{
                ['x-text'](){
                    if(this.showPicker.month){
                        return 'انتخاب ماه'
                    }

                     if(this.showPicker.year){
                        return 'انتخاب سال'
                    }
                }
            },

            close:{
                ['x-on:click'](){
                    this.closeDateNavigationPanel();
                }
            },

            picker :{
    
                ['x-on:click'](){
                    this.showDateNavigationPanel = true
                
                    let buttonDataPicker = this.$el.dataset.picker

                    if(buttonDataPicker == 'months'){
                        this.showPicker.month = true
                    }

                    if(buttonDataPicker == 'years'){
                        this.showPicker.year=true
                    }

                }
            },

            button : {
                type : 'button',

                [':class'](){
                    let buttonClasses = 'rounded-md min-h-9 cursor-pointer flex justify-center items-center border border-blue-100 '

                    if(this.$el.dataset.selected){
                         buttonClasses += 'bg-blue-100 text-blue-700'
                    }

                    return buttonClasses
                },

                ['x-text'](){
                    return this.$el.value
                },

                ['x-on:click'](){
                   Livewire.hook('request', ({ succeed }) => {
                        succeed(() => {
                            this.closeDateNavigationPanel();
                        })
                    })
                }
            }
        },


        weekdays: {
        [':class'](){
            let headerClass = "bg-color-700 text-color-100"
            return this.changeCalendarColor(headerClass)
            }
        },


        // Defines the bindings for days that cannot be selected.
        unselectableDays:{
            type : 'button',
            disabled : true,

            [':class'](){
                let unselectableButtonsClass = 'h-9 rounded-md px-3 bg-color-100 opacity-50 text-color-700'
                return this.changeCalendarColor(unselectableButtonsClass)
            },

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
                this.selectedDate = this.$el.value 

                if(this.$wire.get('autoClose')){
                    this.showCalendar =false;
                }

            },

            // Applies the appropriate styles to the day based on today's
            // date and the currently selected date.
            [':class'](){ 

                let selectableButtonsClass ='h-9 rounded-md px-3 cursor-pointer '

                if(this.selectedDate == this.$el.value){
                        selectableButtonsClass += 'bg-color-700 text-color-100 font-semibold duration-300'
                }else{
                        selectableButtonsClass += 'bg-color-100 text-color-700 hover:bg-color-200'
                }

                if (this.$el.dataset.istoday) {
                    selectableButtonsClass += ' border border-color-700 '
                }

                return this.changeCalendarColor(selectableButtonsClass)
            }
        }
    }
}))