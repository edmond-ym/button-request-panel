# Virtual Device

We have provided the Open Source Simulator for you to
simulate the Hardware Button.


1. Clone the Simulator from the Github

Open the Terminal, go to the folder you desire, and type the following:

```bash
git clone https://github.com/edmond95155156/button-request-device-simulator.git
```

2. Configure the Simulator
In the Simulator, there are `app.html` file and `config` folder.

Inside the `config` folder, there is `deviceData.js` file.

After you open the `deviceData.js` file, you will see the following.

```js
const DeviceData=[
    {
        device_id:"(UUID)",
        bearer_token:"(Token)",
        button_list:[
            {id:"(Button Id)", nickname:"(Nickname)"},
        ]
    },
];
const APILink="(API Base Url)";
```

You should replace the String with the () Bracket with the correct string.
There can be more than 1 button in each device. To do that, you just need to
add the following into the `button_list` field.

```js
{id:"(Button Id)", nickname:"(Nickname)"},
```

It will become:
```js
const DeviceData=[
    {
        device_id:"(UUID)",
        bearer_token:"(Token)",
        button_list:[
            {id:"(Button Id)", nickname:"(Nickname)"}, //First Button
            {id:"(Button Id)", nickname:"(Nickname)"}, //Second Button
        ]
    },
];
const APILink="(API Base Url)";
```

Also, there can be more than 1 device to be tested in a single Simulator. You just need to
do the following, 

```js
const DeviceData=[
    //First Device
    {
        device_id:"(UUID)",
        bearer_token:"(Token)",
        button_list:[
            {id:"(Button Id)", nickname:"(Nickname)"},
            ...
        ]
    },
    //Second Device
    {
        device_id:"(UUID)",
        bearer_token:"(Token)",
        button_list:[
            {id:"(Button Id)", nickname:"(Nickname)"},
            ...
        ]
    },
];
const APILink="(API Base Url)";
```

3. Open the app.html

Double click the `app.html` in the parent folder to execute the simulator.