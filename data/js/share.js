function toggleContentDiv2(element) {
    // 获取父类相关元素
    let contentDiv2 = element.parentElement.querySelector('.content_div_2');
    let content_text = element.parentElement.querySelector('.content_content_text');
    let parentDiv = element.parentElement;

    // 获取子类相关元素
    let title = element.querySelector('.content_title');
    let text = element.querySelector('.content_text');
    let content_title = element.querySelector('.content_content_title');

    // 判断内容是否可见
    let isContentVisible = contentDiv2.style.display === 'block';

    // 根据内容可见性设置显示样式
    contentDiv2.style.display = isContentVisible ? 'none' : 'block';
    content_title.style.display = isContentVisible ? 'none' : 'block';
    content_text.style.display = isContentVisible ? 'none' : 'block';
    title.style.display = isContentVisible ? 'block' : 'none';
    text.style.display = isContentVisible ? 'block' : 'none';

    // 调整父元素的类
    parentDiv.classList.remove(isContentVisible ? 'content_2' : 'content_1');
    parentDiv.classList.add(isContentVisible ? 'content_1' : 'content_2');

    // 调整当前元素的类
    element.classList.remove(isContentVisible ? 'content_div_1_unfold' : 'content_div_1_merge');
    element.classList.add(isContentVisible ? 'content_div_1_merge' : 'content_div_1_unfold');
}

function redirect(Message) {
    // 获取当前页面的原始部分和路径部分
    const originAndPath = window.location.origin + window.location.pathname;

    // 如果路径部分末尾有斜杠，去除之
    const newPath = originAndPath.endsWith('/') ? originAndPath.slice(0, -1) : originAndPath;
    console.log(newPath);

    // 重定向到新的URL
    window.location.href = newPath + Message;
}

function expandFirstElement(enableExpansion) {
    // 获取第一个元素
    let element = document.querySelector('.content');

    if (enableExpansion) {
        // 使用 classList 操作类
        element.classList.remove('content_1');
        element.classList.add('content_2');

        // 缓存选择器的结果
        let div1 = element.querySelector(".content_div_1");
        let title = element.querySelector(".content_content_title");
        let text = element.querySelector(".content_content_text");
        let div2 = element.querySelector(".content_div_2");
        let contentTitle = element.querySelector(".content_title");
        let contentText = element.querySelector(".content_text");

        // 使用 classList 操作类
        div1.classList.remove('content_div_1_merge');
        div1.classList.add('content_div_1_unfold');
        title.style.display = "block";
        text.style.display = "block";
        div2.style.display = "block";
        contentTitle.style.display = "none";
        contentText.style.display = "none";
    }
}
