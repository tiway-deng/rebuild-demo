最近一直在学习DDD相关内容，通过近几个月的学习也确实感觉到我们现有项目的一些缺陷。

### 项目介绍
目前项目项目架构
![在这里插入图片描述](https://img-blog.csdnimg.cn/62d78a0ce67a4c95ace10f13f2fb4a3b.png?x-oss-process=image/watermark,type_d3F5LXplbmhlaQ,shadow_50,text_Q1NETiBAdGl3YXlEZW5n,size_17,color_FFFFFF,t_70,g_se,x_16)
项目框架：YII2
1.在controller 层得到对应版本号映射对应的service,每个版本对应一个service
~~~php
public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        //获取对应service版本
        $servicePath = 'myService';
        $retServicePath = $this->getServiceVersion($servicePath);
        //我的service
        $this->service = new $retServicePath();
    }
~~~

2.版本间共用方法通过commonService调用
3.版本对应的service承载业务逻辑及数据查询
~~~php
namespace app\service\v1\v1_0_0\myService
//...
public function someThingCreate($params)
    {
        if (condition1) {
            throw new BusinessException('condition1 不满足');
        }
        if (condition2) {
            throw new BusinessException('condition2 不满足');
        }
        //...
       //数据查询
        $repositoryOne = OneRepository::getOne($condition);
        if (!$repositoryOne) {
            throw new BusinessException('找不到数据');
        }
        //....
       
        //开启事务
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //添加two
            $two= TwoRepository::create($params);
			//添加three
            $three= ThreeRepository::create($params);
       
            $transaction->commit();
        } catch (\Throwable $e) {
           //...
        }
    }
~~~
4.repository承载所有的对mysql 数据库的操作
~~~php
namespace app\repository\db\myRepository;

public static function oneCreate($attributes)
    {
        $one= new one();
        $one->setAttributes($attributes);
        $one->save();
        return $one;
    }
~~~
5.model建立关联及验证相关关系
~~~php
class One extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'One';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           //...
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //...
        ];
    }
    
	public function getTwo()
    {
        return $this->hasOne(Two::class, ['id' => 'one_id']);
    }

}
~~~
#### 项目存在问题
1.版本迭代需要同事修改历史版本的controller与新的controller，从controller层找到服务层只能找对应版本的目录service,不能通过IDE跳转,如下只能跳转到1.0.0的版本
~~~php
 use app\service\v1\v1_0_0\myService
  /**
     * @var myService
     */
    protected $service;
~~~
2.业务代码的清晰度不够
- 对于历史版本的业务逻辑有些需要提升到commservice,什么时候提，谁来提，怎么知道要不要提
- 存在大量commonService ，但没有针对每个commonService进行边界设定

3.可测性
- params参数具体定义；
- 针对每个params的字段验证成倍数增长，即params[1]有m种，params[2]有n种。。。，则需要m*n*...测试成本巨大

#### 解决方案
我们将尝试应用DDD的思想来对我们的业务及架构进行梳理
![在这里插入图片描述](https://img-blog.csdnimg.cn/43e3eebce2f34d87a904051c89ae6f26.png?x-oss-process=image/watermark,type_d3F5LXplbmhlaQ,shadow_50,text_Q1NETiBAdGl3YXlEZW5n,size_20,color_FFFFFF,t_70,g_se,x_16)

从整体架构来说没有太大的变化，主要的变更变更包含
1.controller与service跟随版本走
2.把对应的use case 抽象成一个域对象，域对象包含行为和属性，域对象不直接依赖于数据库，通过interface repositorySpecification的注入查询数据对数据的验证与查询
3.查询类的语句不封装在repository 直接在service层通过Repository调用查询

我们用一个案例来解析说明，因涉及项目安全问题，已隐藏部分逻辑
1.通过YII2 创建对应版本
[模块化](https://www.yiichina.com/doc/guide/2.0/structure-modules)，
[版本控制](https://www.yiichina.com/doc/guide/2.0/rest-versioning)，
[语义版本化](https://semver.org/lang/zh-CN/)
![在这里插入图片描述](https://img-blog.csdnimg.cn/f2709f027f1d4140912c566f9568f8f2.png)

2.创建对应的Domain
![在这里插入图片描述](https://img-blog.csdnimg.cn/fb1805949161458a9a91433e43ccfdb2.png?x-oss-process=image/watermark,type_d3F5LXplbmhlaQ,shadow_50,text_Q1NETiBAdGl3YXlEZW5n,size_14,color_FFFFFF,t_70,g_se,x_16)

1.valueObject 将隐性的概念显性化，集中验证的重要的对象属性
~~~php
<?php
namespace app\domain\activityCategory\valueObject;


use app\helper\Assert;

final class CategoryName
{
    private $name;

    public static function fromString(string $param): self
    {
        Assert::stringNotEmpty($param, '%s 不能为空');
        Assert::maxLength($param, 12,'%s 不能超过12个字符');

        $CategoryName = new self();

        $CategoryName->name = $param;

        return $CategoryName;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    private function __construct()
    {
    }

}
~~~
2.实体
能够创建唯一标签的域对象，这些有标识的概念有长期存在的特性。无论概念中的数据发生多少次变化，它们的标识总是相同。区别于我们的我们表的数据映射，没有必然的直接的联系，比如activity 这个实体我实际可以分为activity   activity_content activity_address 等几个表
~~~php
<?php

namespace app\domain\activityCategory;


use app\domain\activityCategory\specification\CategorySpecificationInterface;
use app\domain\activityCategory\valueObject\CategoryDescription;
use app\domain\activityCategory\valueObject\CategoryName;
use app\domain\activityCategory\valueObject\CategorySort;
use app\helper\Assert;

class ActivityCategory
{
    private $id;
    private $name;
    private $description;
    private $sort;
    private $created_at;
    private $updated_at;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        if (is_null($this->created_at)) {
            $this->setCreatedAt(time());
        }
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        if (is_null($this->updated_at)) {
            $this->setUpdatedAt(time());
        }
        return $this->updated_at;
    }


    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public static function create(
        CategoryName $name,
        CategoryDescription $categoryDescription,
        CategorySort $sort,
        CategorySpecificationInterface $specification
    ): self
    {
        if($specification->isUniqueName($name)){
            Assert::reportInvalidArgument('分类名称重复');
        }

        $category = new self();
        $category->setName($name);
        $category->setDescription($categoryDescription);
        $category->setSort($sort);

        return $category;
    }

}
~~~
3.specification
实体不直接调用数据层，通过specificationInterface 来查询及验证对应的数据
~~~php
<?php
namespace app\domain\activityCategory\specification;

use app\domain\activityCategory\valueObject\CategoryId;
use app\domain\activityCategory\valueObject\CategoryName;

interface CategorySpecificationInterface
{
    public function isUniqueName(CategoryName $name): bool;
    public function isCategoryExits(CategoryId $id): bool;

}


~~~


#### 总结
1.  以上是我们对于DDD的一些探索，确实解决了我们项目中的一些问题
2.  未能完全应用DDD思想，如 实体对象保存（保存实体状态至数据库中最好的工具就是 Doctrine ORM，目前YII2自带ORM不能直接保存实体对象），领域事件等，如果全部需要全部应用DDD感觉需要转为symfony可能风险更小
3. 目前只是在DDD战术思想上的应用，对于真正的理解仍需探索，需要整个团队共同努力

[csdn](https://blog.csdn.net/qq_39941141/article/details/124164273)