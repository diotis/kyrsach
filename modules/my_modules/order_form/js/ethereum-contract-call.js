(function ($, Drupal) {
    Drupal.behaviors.ethereumOrderForm = {
        attach: function (context, settings) {
            if(document.getElementById('my-form'))
                document.getElementById('block-store-content').innerHTML += '<button id="blockchain">Contract</button>';
            // contract abi
            var abi = [{
                "constant":true,
                "inputs":[],
                "name":"get",
                "outputs":[{"name":"","type":"string"}],
                "payable":false,
                "stateMutability":"view",
                "type":"function"
            }, {
                "constant":false,
                "inputs":[{"name":"_data","type":"string"}],
                "name":"add",
                "outputs":[{"name":"","type":"string"}],
                "payable":false,
                "stateMutability":"nonpayable",
                "type":"function"
            }, {
                "inputs":[],
                "payable":false,
                "stateMutability":"nonpayable",
                "type":"constructor"}];

            $('#blockchain').bind('click', function(){
                if(initWeb3()) {
                    var MyContract = web3.eth.contract(abi);
                    var myContractInstance = MyContract.at('0xa76dE0289Cd8346E53E1b1f690608bcdF946988f');
                    myContractInstance.add('myParam', function (err,result) {
                        if(!err)
                            console.log(result);
                        else console.log(err);
                    });
                }
            });

            var message = document.getElementById('myMessage');
            function liveLog(content) {
                var log = $('#littleLog');
                if (!log.length) {
                    $('#myMessage').after('<div id="littleLog" class="color-success"></div>');
                    log = $('#littleLog').css({
                        fontSize: '80%',
                        padding: '0 .25em',
                        display: 'inline-block',
                        marginTop: '0.5em'
                    });
                }
                if (content === false) {
                    log.remove();
                }
                else {
                    log.text(content);
                }
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
        }
    };

})(window.jQuery, window.Drupal);