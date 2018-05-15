(function ($, Drupal, settings) {
    Drupal.behaviors.user_contracts = {
        attach: function (context) {
            $(document).ready(function () {
                var location = (window.location.href).replace("https://mystore/user/", "");
                console.log('user');

                function go(id) {
                    window.open("//mystore/contract/" + id);
                }

                $("select").change(function () {
                    var tr = $(this).parent().parent().parent();
                    var id = this.getAttribute('data');
                    var nid = tr[0].getAttribute('node');
                    var value = $(this).children(":selected")[0].getAttribute('value');
                    console.log(value);
                    if (value == 'go') {
                        go(id);
                    }
                    else if (value == 'view') {
                        window.open("//mystore/node/" + nid);
                    }
                    else if (value == 'end') {
                        ajax(value, id, function (data) {
                            console.log(data);
                            // data_log(data, function () {
                            $(tr).css('background-color','red');
                            // });
                        });
                    }
                    else if(value == 'contract'){
                        var abi = [{
                            "constant":false,
                                "inputs":[
                                    {"name":"_user_id","type":"uint256"},
                                    {"name":"_contract_id","type":"uint256"},
                                    {"name":"_date","type":"string"},
                                    {"name":"_caption","type":"string"},
                                    {"name":"_data","type":"string"}
                                    ],
                                "name":"add",
                                "outputs":[],
                                "payable":false,
                                "stateMutability":"nonpayable"
                                ,"type":"function"
                            },{
                            "constant":true,
                                "inputs":[],
                                "name":"OwnerContractCount",
                                "outputs":[{"name":"","type":"uint256"}],
                                "payable":false,
                                "stateMutability":"view",
                                "type":"function"
                            },{
                            "constant":false,
                                "inputs":[{"name":"i","type":"uint256"}],
                                "name":"getContractInformation",
                                "outputs":[],
                                "payable":false,
                                "stateMutability":"nonpayable",
                                "type":"function"
                            },{
                            "inputs":[],
                                "payable":false,
                                "stateMutability":"nonpayable",
                                "type":"constructor"
                            },{
                            "anonymous":false,
                                "inputs":[{"indexed":false,"name":"","type":"uint256"}],
                                "name":"id","type":"event"
                            },{
                            "anonymous":false,
                                "inputs":[{"indexed":false,"name":"","type":"uint256"}],
                                "name":"user_id","type":"event"
                            },{
                            "anonymous":false,
                                "inputs":[{"indexed":false,"name":"","type":"uint256"}],
                                "name":"contract_id","type":"event"
                            },{
                            "anonymous":false,
                                "inputs":[{"indexed":false,"name":"","type":"string"}],
                                "name":"date","type":"event"
                            },{
                            "anonymous":false,
                                "inputs":[{"indexed":false,"name":"","type":"string"}],
                                "name":"caption","type":"event"
                            },{
                            "anonymous":false,
                                "inputs":[{"indexed":false,"name":"","type":"string"}],
                                "name":"data",
                                "type":"event"
                        }];
                        //сообщение ожидания
                        ajax(value, id, function (data) {
                            data = JSON.parse(data)[0];
                            console.log(data);

                            if(initWeb3()) {
                                var MyContract = web3.eth.contract(abi);
                                var myContractInstance = MyContract.at('0x3bcf5b2d140795c9a436Cc0A470673d2880f30F7');
                                myContractInstance.add(data['user_id'],data['id'],data['date'],data['caption'],data['data'], function (err,result) {
                                    if(!err){
                                        ajax('transaction',result,function () {
                                            console.log('save transaction: '+result);
                                            alert("hash: "+result);
                                        });
                                    }
                                    else printError(data);
                                });
                            }
                        });




                    }
                    else {
                        if(value!=0)
                        ajax(value, id, function (data) {
                            console.log(data);
                            // data_log(data, function () {
                            $(tr).remove();
                            // });
                        });
                    }
                });
                function ajax(value, id, callback) {
                    console.log('/user/'+location+'/'+value+'/'+id);
                    $.ajax({
                        type: 'get',
                        url: '/user/'+location+'/'+value+'/'+id,
                        success: function (data) {
                            callback(data);
                        },
                        error: function (jqXHR, error, errorThrown) {
                            console.log(jqXHR, error, errorThrown);
                            printError(error);
                        }
                    });
                }

//contract
                var message = document.getElementById('myMessage');
                function liveLog(content) {
                    console.log('log: '+content)
                    // var log = $('#littleLog');
                    // if (!log.length) {
                    //     $('#myMessage').after('<div id="littleLog" class="color-success"></div>');
                    //     log = $('#littleLog').css({
                    //         fontSize: '80%',
                    //         padding: '0 .25em',
                    //         display: 'inline-block',
                    //         marginTop: '0.5em'
                    //     });
                    // }
                    // if (content === false) {
                    //     log.remove();
                    // }
                    // else {
                    //     log.text(content);
                    // }
                }
                function initWeb3() {
                    liveLog('Initialize web3');
                    if (typeof web3 !== 'undefined') {
                        web3 = new Web3(web3.currentProvider);
                        liveLog('Web3 initialized');
                        return true;
                    }
                    else {
                        // Add missing web3.js message.
                        var msg ='Currently <a href="https://metamask.io/"> Metamask</a> Chrome(ium) extension and  <a href="https://github.com/ethereum/mist/releases"> Mist browser</a> are supported and required as transaction signer. Get <a href="https://chrome.google.com/webstore/detail/metamask/nkbihfbeogaeaoehlefnkodbefgpgknn">metamask extension here</a>'
                        message.innerHTML += Drupal.theme('message', msg, 'error');
                        return false;
                    }
                }


                function printError(msg) {
                    document.getElementById('page-info').innerHTML = msg;
                }











            });
        }
    }
})(jQuery, Drupal, drupalSettings);