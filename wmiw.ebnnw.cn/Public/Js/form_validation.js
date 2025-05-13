/**
 * 古董拍卖网站表单验证增强脚本
 * 为登录和注册表单添加实时输入提示和验证反馈
 */

$(function() {
    // 添加输入提示容器
    $('.am-form input[name="account"]').after('<div class="input-tip" id="account-tip">账号必须字母开头，字母、数字、下划线组合的3至19个字符</div>');
    $('.am-form input[name="pwd"]').after('<div class="input-tip" id="pwd-tip">密码为6-30个字符</div>');
    
    // 设置初始样式
    $('.input-tip').css({
        'color': '#666',
        'font-size': '16px',
        'margin': '5px 0 15px 0',
        'padding': '5px 10px',
        'background-color': '#f9f5f0',
        'border-radius': '4px',
        'transition': 'all 0.3s ease'
    });
    
    // 账号输入验证
    $('.am-form input[name="account"]').on('input', function() {
        var account = $(this).val();
        var accountRegex = /^[a-zA-Z][a-zA-Z0-9_]{2,18}$/;
        
        if (account === '') {
            $('#account-tip').css('color', '#666').text('账号必须字母开头，字母、数字、下划线组合的3至19个字符');
        } else if (!accountRegex.test(account)) {
            $('#account-tip').css({
                'color': '#8B0000',
                'background-color': '#ffeeee'
            }).text('账号格式不正确，必须字母开头，字母、数字、下划线组合的3至19个字符');
        } else {
            $('#account-tip').css({
                'color': '#006400',
                'background-color': '#eeffee'
            }).text('账号格式正确');
        }
    });
    
    // 密码输入验证
    $('.am-form input[name="pwd"]').on('input', function() {
        var pwd = $(this).val();
        var pwdRegex = /^.{6,30}$/;
        
        if (pwd === '') {
            $('#pwd-tip').css('color', '#666').text('密码为6-30个字符');
        } else if (!pwdRegex.test(pwd)) {
            $('#pwd-tip').css({
                'color': '#8B0000',
                'background-color': '#ffeeee'
            }).text('密码长度不符合要求，应为6-30个字符');
        } else {
            $('#pwd-tip').css({
                'color': '#006400',
                'background-color': '#eeffee'
            }).text('密码格式正确');
        }
    });
    
    // 增强按钮交互体验
    $('.am-btn').hover(
        function() {
            $(this).css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
    
    // 表单提交前验证
    $('.am-form').on('submit', function(e) {
        var account = $('input[name="account"]').val();
        var pwd = $('input[name="pwd"]').val();
        var accountRegex = /^[a-zA-Z][a-zA-Z0-9_]{2,18}$/;
        var pwdRegex = /^.{6,30}$/;
        
        // 清除之前的错误提示
        $('.form-error-msg').remove();
        
        var hasError = false;
        
        if (!accountRegex.test(account)) {
            $('input[name="account"]').after('<div class="form-error-msg">账号格式不正确</div>');
            hasError = true;
        }
        
        if (!pwdRegex.test(pwd)) {
            $('input[name="pwd"]').after('<div class="form-error-msg">密码格式不正确</div>');
            hasError = true;
        }
        
        // 如果有错误，阻止表单提交
        if (hasError) {
            e.preventDefault();
            
            // 添加错误样式
            $('.form-error-msg').css({
                'color': '#8B0000',
                'font-size': '16px',
                'margin': '5px 0',
                'font-weight': 'bold'
            });
            
            // 滚动到第一个错误
            $('html, body').animate({
                scrollTop: $('.form-error-msg:first').offset().top - 100
            }, 500);
        }
    });
    
    // 为验证码添加点击刷新提示
    $('.verifyImg').attr('title', '点击刷新验证码').css('cursor', 'pointer');
});
