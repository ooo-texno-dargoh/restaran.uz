<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\AsosSlave;

/**
 * AsosSlaveSearch represents the model behind the search form about `frontend\models\AsosSlave`.
 */
class AsosSlaveSearch extends AsosSlave
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tovar_id', 'izm_id', 'izm1', 'tulov_id', 'turi', 'resept', 'qrkod', 'kol', 'kol_in', 'subkod', 'user_id', 'asos_id', 'del_flag', 'kol_ost', 'kol_in_ost', 'zakaz_see', 'zakaz_print', 'zakaz_gapir', 'ch2'], 'integer'],
            [['tovar_nom', 'seriya', 'polka', 'srok', 'changedate', 'zakaz', 'zakaz_ok', 'zakaz_end'], 'safe'],
            [['sena', 'sena_in', 'summa', 'summa_in', 'summa_all', 'sotish', 'sotish_in', 'foiz', 'foiz_in', 'summa_ost', 'summa_in_ost', 'summa_all_ost', 'sena_d', 'sena_in_d', 'sotish_d', 'sotish_in_d', 'foiz_ch2', 'foiz_schet', 'foiz_opt1', 'foiz_opt2', 'opt1', 'opt1_pl', 'opt1_in', 'opt1_pl_in', 'opt2', 'opt2_pl', 'opt2_in', 'opt2_pl_in', 'schet', 'schet_in', 'ch2_in'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AsosSlave::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tovar_id' => $this->tovar_id,
            'izm_id' => $this->izm_id,
            'izm1' => $this->izm1,
            'tulov_id' => $this->tulov_id,
            'srok' => $this->srok,
            'turi' => $this->turi,
            'resept' => $this->resept,
            'qrkod' => $this->qrkod,
            'kol' => $this->kol,
            'kol_in' => $this->kol_in,
            'sena' => $this->sena,
            'sena_in' => $this->sena_in,
            'summa' => $this->summa,
            'summa_in' => $this->summa_in,
            'summa_all' => $this->summa_all,
            'sotish' => $this->sotish,
            'sotish_in' => $this->sotish_in,
            'foiz' => $this->foiz,
            'foiz_in' => $this->foiz_in,
            'subkod' => $this->subkod,
            'user_id' => $this->user_id,
            'changedate' => $this->changedate,
            'asos_id' => $this->asos_id,
            'del_flag' => $this->del_flag,
            'kol_ost' => $this->kol_ost,
            'kol_in_ost' => $this->kol_in_ost,
            'summa_ost' => $this->summa_ost,
            'summa_in_ost' => $this->summa_in_ost,
            'summa_all_ost' => $this->summa_all_ost,
            'sena_d' => $this->sena_d,
            'sena_in_d' => $this->sena_in_d,
            'sotish_d' => $this->sotish_d,
            'sotish_in_d' => $this->sotish_in_d,
            'zakaz' => $this->zakaz,
            'zakaz_ok' => $this->zakaz_ok,
            'zakaz_end' => $this->zakaz_end,
            'zakaz_see' => $this->zakaz_see,
            'zakaz_print' => $this->zakaz_print,
            'zakaz_gapir' => $this->zakaz_gapir,
            'foiz_ch2' => $this->foiz_ch2,
            'foiz_schet' => $this->foiz_schet,
            'foiz_opt1' => $this->foiz_opt1,
            'foiz_opt2' => $this->foiz_opt2,
            'opt1' => $this->opt1,
            'opt1_pl' => $this->opt1_pl,
            'opt1_in' => $this->opt1_in,
            'opt1_pl_in' => $this->opt1_pl_in,
            'opt2' => $this->opt2,
            'opt2_pl' => $this->opt2_pl,
            'opt2_in' => $this->opt2_in,
            'opt2_pl_in' => $this->opt2_pl_in,
            'schet' => $this->schet,
            'schet_in' => $this->schet_in,
            'ch2' => $this->ch2,
            'ch2_in' => $this->ch2_in,
        ]);

        $query->andFilterWhere(['like', 'tovar_nom', $this->tovar_nom])
            ->andFilterWhere(['like', 'seriya', $this->seriya])
            ->andFilterWhere(['like', 'polka', $this->polka]);

        return $dataProvider;
    }
}
